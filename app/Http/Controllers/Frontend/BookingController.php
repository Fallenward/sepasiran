<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\City;
use App\Support\BookingCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $cities = City::query()->orderBy('name')->get(['id', 'name']);

        return view('frontend.pages.booking.index', [
            'cities' => $cities,
            'prefill' => [
                'service_type' => $request->get('type', 'coach'),
                'provider_id' => (int) $request->get('provider', 0),
                'city_id' => (int) $request->get('city_id', 0),
                'date' => $request->get('date', now()->addDay()->format('Y-m-d')),
            ],
            'authUser' => Auth::user(),
            'apiBase' => url('/booking/api'),
            'loginUrl' => route('login'),
            'csrf' => csrf_token(),
            'isAuthenticated' => Auth::check(),
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'));
        }

        try {
            $api = app(BookingApiController::class);
            $response = $api->store($request);
            $payload = $response->getData(true);

            return redirect()->to($payload['success_url'] ?? ('/booking/success/'.$payload['booking']['booking_code']));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function success(string $code)
    {
        $booking = Booking::with(['provider', 'breed', 'city', 'dog.breed', 'offers.provider', 'invitedProviders'])
            ->where('booking_code', $code)
            ->firstOrFail();
        $user = Auth::user();

        $allowed = $user && (
            (int) $booking->user_id === (int) $user->id
            || (int) $booking->provider_id === (int) $user->id
            || (method_exists($user, 'hasRole') && $user->hasRole('admin'))
            || !empty($user->is_admin)
        );

        if (!$allowed) {
            abort(403);
        }

        $api = app(BookingApiController::class);

        return view('frontend.pages.booking.success', [
            'booking' => $api->serialize($booking),
            'serviceLabels' => collect(BookingCatalog::serviceTypes())->pluck('label', 'key'),
            'modeLabels' => collect(BookingCatalog::fulfillmentModes())->pluck('label', 'key'),
        ]);
    }

    public function providersShim(Request $request)
    {
        if (!$request->filled('service_type') && $request->filled('type')) {
            $request->merge(['service_type' => $request->get('type')]);
        }

        return app(BookingApiController::class)->providers($request);
    }

    /**
     * Public marketplace for open booking requests (not in account dashboard).
     */
    public function openBoard(Request $request)
    {
        $serviceType = $request->query('service_type');
        $query = Booking::query()
            ->with(['city', 'dog.breed', 'user'])
            ->withCount(['offers', 'messages'])
            ->where('fulfillment_mode', 'open')
            ->where('status', 'OPEN')
            ->orderByDesc('id');

        if ($serviceType && in_array($serviceType, BookingCatalog::keys(), true)) {
            $query->where('service_type', $serviceType);
        }

        $user = Auth::user();
        $mineOpen = collect();
        if ($user) {
            $mineOpen = Booking::query()
                ->with(['city', 'dog.breed'])
                ->withCount(['offers', 'messages'])
                ->where('user_id', $user->id)
                ->where('fulfillment_mode', 'open')
                ->whereIn('status', ['OPEN', 'ACCEPTED', 'CANCELLED'])
                ->orderByDesc('id')
                ->limit(20)
                ->get();
        }

        return view('frontend.pages.booking.open-board', [
            'bookings' => $query->paginate(20)->withQueryString(),
            'mineOpen' => $mineOpen,
            'serviceTypes' => BookingCatalog::serviceTypes(),
            'selectedService' => $serviceType,
            'authUser' => $user,
            'isAuthenticated' => Auth::check(),
            'loginUrl' => route('login', ['redirect' => url()->current()]),
        ]);
    }

    public function openShow(string $code)
    {
        $booking = Booking::query()
            ->with([
                'provider',
                'user',
                'dog.breed',
                'city',
                'offers.provider',
                'messages.user',
            ])
            ->where('booking_code', $code)
            ->where('fulfillment_mode', 'open')
            ->firstOrFail();

        $user = Auth::user();
        $isOwner = $user && (int) $booking->user_id === (int) $user->id;
        $role = BookingCatalog::roleFor((string) $booking->service_type);
        $isProvider = $user
            && method_exists($user, 'hasRole')
            && $role
            && $user->hasRole($role)
            && ! $isOwner;
        $isAdmin = $user && (
            ! empty($user->is_admin)
            || (method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('is_admin')))
        );

        // Guests can view open listings; actions need auth.
        if (strtoupper((string) $booking->status) !== 'OPEN' && ! $isOwner && ! $isAdmin) {
            // After accept, only parties see detail.
            abort_unless(
                $user && (
                    $isOwner
                    || (int) $booking->provider_id === (int) $user->id
                    || $booking->offers()->where('provider_id', $user->id)->exists()
                    || $isAdmin
                ),
                403
            );
        }

        $api = app(BookingApiController::class);

        return view('frontend.pages.booking.open-show', [
            'booking' => $booking,
            'serialized' => $api->serialize($booking->loadMissing(['offers.provider', 'invitedProviders', 'dog.breed', 'city', 'provider', 'user'])),
            'isOwner' => (bool) $isOwner,
            'isProvider' => (bool) $isProvider,
            'isAuthenticated' => Auth::check(),
            'loginUrl' => route('login', ['redirect' => url()->current()]),
            'authUser' => $user,
            'serviceLabels' => collect(BookingCatalog::serviceTypes())->pluck('label', 'key'),
        ]);
    }
}