<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingHubController extends Controller
{
    /** Customer: my reservation requests / bookings. */
    public function mine(): View
    {
        $user = Auth::user();

        $bookings = Booking::query()
            ->where('user_id', $user->id)
            ->with(['provider', 'dog'])
            ->orderByRaw('COALESCE(appointment_date, preferred_date, booking_date) desc')
            ->paginate(20);

        return view('frontend.pages.account.bookings', [
            'user' => $user,
            'bookings' => $bookings,
            'mode' => 'mine',
            'roleLabel' => $user->primaryRoleLabel(),
            'backUrl' => $user->dashboardRoute(),
        ]);
    }

    /** Provider inbox: coach / clinic / breeder incoming requests. */
    public function inbox(): View
    {
        $user = Auth::user();

        abort_unless(
            $user->hasRole('coach') || $user->hasRole('clinic') || $user->hasRole('breeder'),
            403
        );

        $bookings = Booking::query()
            ->where('provider_id', $user->id)
            ->with(['user', 'dog'])
            ->orderByRaw('COALESCE(appointment_date, preferred_date, booking_date) desc')
            ->paginate(20);

        return view('frontend.pages.account.bookings', [
            'user' => $user,
            'bookings' => $bookings,
            'mode' => 'inbox',
            'roleLabel' => $user->primaryRoleLabel(),
            'backUrl' => $user->dashboardRoute(),
        ]);
    }
}
