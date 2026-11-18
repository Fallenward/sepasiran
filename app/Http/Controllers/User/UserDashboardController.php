<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Support\DashboardFinance;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $upcomingBookings = Booking::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['PENDING', 'CONFIRMED', 'OPEN', 'QUOTED'])
            ->orderByRaw('COALESCE(appointment_date, preferred_date, booking_date) asc')
            ->limit(8)
            ->get();

        $stats = [
            'wallet' => (int) ($user->wallet ?? 0),
            'upcoming' => $upcomingBookings->count(),
            'dogs' => $user->Dogs()->count(),
        ];

        return view('frontend.pages.user.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'upcomingBookings' => $upcomingBookings,
            'financeChart' => DashboardFinance::monthlySeries($user),
            'roleLabel' => $user->primaryRoleLabel(),
        ]);
    }
}
