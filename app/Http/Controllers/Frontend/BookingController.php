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
        $booking = Booking::with(['provider', 'breed', 'city'])->where('booking_code', $code)->firstOrFail();
        $user = Auth::user();

        $allowed = $user && (
            (int) $booking->user_id === (int) $user->id
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
        ]);
    }

    public function providersShim(Request $request)
    {
        if (!$request->filled('service_type') && $request->filled('type')) {
            $request->merge(['service_type' => $request->get('type')]);
        }

        return app(BookingApiController::class)->providers($request);
    }
}