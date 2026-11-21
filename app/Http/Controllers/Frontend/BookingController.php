<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\City;
use App\Models\User;
use App\Support\BookingCatalog;
use App\Support\BookingPaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $cities = City::query()->orderBy('name')->get(['id', 'name']);
        $providerId = (int) $request->get('provider', 0);
        $cityId = (int) $request->get('city_id', 0);
        $allowedTypes = array_column(BookingCatalog::serviceTypes(), 'key');
        $serviceType = (string) $request->get('type', 'coach');
        if (! in_array($serviceType, $allowedTypes, true)) {
            $serviceType = 'coach';
        }

        $lockedProvider = null;
        if ($providerId > 0) {
            $provider = User::query()->with('city:id,name')->find($providerId);
            if ($provider) {
                $display = trim(($provider->name ?? '').' '.($provider->family ?? ''));
                $lockedProvider = [
                    'id' => (int) $provider->id,
                    'name' => $display !== '' ? $display : (string) ($provider->name ?? 'ارائه‌دهنده'),
                    'city_name' => $provider->city?->name,
                ];
                if ($cityId < 1) {
                    $cityId = (int) ($provider->city_id ?: 0);
                }
            } else {
                $providerId = 0;
            }
        }

        $selectedCityName = $cityId > 0
            ? optional($cities->firstWhere('id', $cityId))->name
            : null;

        $trainingType = (string) $request->get('training_type', '');
        $allowedTraining = array_column(BookingCatalog::trainingTypes(), 'key');
        if ($trainingType !== '' && ! in_array($trainingType, $allowedTraining, true)) {
            $trainingType = '';
        }
        if ($trainingType === '' && $serviceType === 'coach' && $lockedProvider) {
            $trainingType = 'beginner';
        }

        $locked = $lockedProvider !== null;

        return view('frontend.pages.booking.index', [
            'cities' => $cities,
            'selectedCityName' => $selectedCityName,
            'lockedProvider' => $lockedProvider,
            'prefill' => [
                'service_type' => $serviceType,
                'provider_id' => $providerId,
                'city_id' => $cityId,
                'date' => $request->get('date', now()->addDay()->format('Y-m-d')),
                'training_type' => $trainingType,
                'fulfillment_mode' => $locked ? 'direct' : (string) $request->get('mode', 'direct'),
                'locked' => $locked,
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
        $pay = app(BookingPaymentService::class);

        return view('frontend.pages.booking.success', [
            'booking' => $api->serialize($booking),
            'serviceLabels' => collect(BookingCatalog::serviceTypes())->pluck('label', 'key'),
            'modeLabels' => collect(BookingCatalog::fulfillmentModes())->pluck('label', 'key'),
            'paymentRequired' => $pay->needsOnlinePayment($booking)
                && (int) $booking->user_id === (int) optional($user)->id,
        ]);
    }

    public function pay(string $code)
    {
        $booking = Booking::with(['provider', 'breed', 'city', 'dog.breed'])
            ->where('booking_code', $code)
            ->firstOrFail();
        $user = Auth::user();
        abort_unless($user && (int) $booking->user_id === (int) $user->id, 403);

        $pay = app(BookingPaymentService::class);
        if (! $pay->needsOnlinePayment($booking)) {
            return redirect()->route('frontend.booking.success', $booking->booking_code);
        }

        $api = app(BookingApiController::class);

        return view('frontend.pages.booking.pay', [
            'booking' => $api->serialize($booking),
            'bookingModel' => $booking,
            'walletBalance' => (int) ($user->wallet ?? 0),
            'serviceLabels' => collect(BookingCatalog::serviceTypes())->pluck('label', 'key'),
        ]);
    }

    public function processPay(Request $request, string $code, BookingPaymentService $pay)
    {
        $booking = Booking::query()->where('booking_code', $code)->firstOrFail();
        $user = Auth::user();
        abort_unless($user && (int) $booking->user_id === (int) $user->id, 403);

        $data = $request->validate([
            'payment_method' => ['required', 'in:wallet,gateway'],
        ]);

        try {
            if ($data['payment_method'] === 'gateway') {
                $form = $pay->startGatewayPayment($user, $booking);

                return $form instanceof \Shetabit\Multipay\RedirectionForm
                    ? new Response($form->render())
                    : $form;
            }

            $pay->completePayment($user, $booking, 'wallet');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()
            ->route('frontend.booking.success', $booking->booking_code)
            ->with('success', 'پرداخت با موفقیت انجام شد.');
    }

    public function payCallback(Request $request, string $code, BookingPaymentService $pay)
    {
        $booking = Booking::query()->where('booking_code', $code)->firstOrFail();
        $user = Auth::user();
        if ($user && (int) $user->id !== (int) $booking->user_id) {
            abort(403);
        }

        try {
            $pay->completeGatewayPayment(
                $booking,
                $request->input('transactionId') ?: $request->input('Authority')
            );
        } catch (ValidationException $e) {
            return redirect()
                ->route('frontend.booking.pay', $booking->booking_code)
                ->withErrors($e->errors());
        }

        return redirect()
            ->route('frontend.booking.success', $booking->booking_code)
            ->with('success', 'پرداخت درگاه تأیید شد.');
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
                'dog.media',
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