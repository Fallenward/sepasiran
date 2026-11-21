<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CourseEnrollment;
use App\Models\Dog;
use App\Models\InsuranceLicense;
use App\Models\Notification;
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

        $myDogs = Dog::query()
            ->where('user_id', $user->id)
            ->with(['breed', 'media'])
            ->latest()
            ->limit(6)
            ->get();

        $insuranceLicenses = InsuranceLicense::query()
            ->forUser((int) $user->id)
            ->with(['insurance', 'plan'])
            ->latest('ends_at')
            ->limit(5)
            ->get();
        $insuranceLicenses->each(fn (InsuranceLicense $license) => $license->refreshStatus());

        $courseEnrollments = CourseEnrollment::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['course:id,title,slug'])
            ->latest('id')
            ->limit(5)
            ->get();

        $stats = [
            'wallet' => (int) ($user->wallet ?? 0),
            'upcoming' => $upcomingBookings->count(),
            'dogs' => Dog::query()->where('user_id', $user->id)->count(),
            'insurance' => InsuranceLicense::query()->forUser((int) $user->id)->active()->count(),
            'courses' => CourseEnrollment::query()->where('user_id', $user->id)->where('status', 'active')->count(),
        ];

        return view('frontend.pages.user.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'myDogs' => $myDogs,
            'upcomingBookings' => $upcomingBookings,
            'insuranceLicenses' => $insuranceLicenses,
            'courseEnrollments' => $courseEnrollments,
            'financeChart' => DashboardFinance::monthlySeries($user),
            'dashNotifications' => Notification::query()->where('user_id', $user->id)->latest()->limit(5)->get(),
            'roleLabel' => $user->primaryRoleLabel(),
        ]);
    }
}
