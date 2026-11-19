<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Dog;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Support\DashboardFinance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Unauthorized access.');
        }

        $dashNotifications = Notification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $role = collect(['admin', 'breeder', 'coach', 'clinic'])
            ->first(fn ($name) => $user->hasRole($name));

        if (! $role && ! empty($user->is_admin)) {
            $role = 'admin';
        }

        if (! $role) {
            abort(403, 'Access denied. Your role does not have a dashboard.');
        }

        return match ($role) {
            'clinic' => $this->clinicDashboard($user, $dashNotifications),
            'coach' => $this->coachDashboard($user, $dashNotifications),
            'breeder' => $this->breederDashboard($user, $dashNotifications),
            'admin' => $this->adminDashboard($user, $dashNotifications),
            default => abort(403, 'Access denied. Your role does not have a dashboard.'),
        };
    }

    private function clinicDashboard(User $user, $dashNotifications): View
    {
        $providerBookings = Booking::query()
            ->where('provider_id', $user->id)
            ->whereRaw('LOWER(service_type) = ?', ['clinic']);

        $activeVisits = (clone $providerBookings)->where('status', 'CONFIRMED')->count();
        $pendingVisits = (clone $providerBookings)->whereIn('status', ['PENDING', 'OPEN', 'QUOTED'])->count();
        $clinicIncome = (clone $providerBookings)->where('status', 'COMPLETED')->sum('total')
            ?: (clone $providerBookings)->where('status', 'COMPLETED')->sum('price');

        $recentBookings = (clone $providerBookings)
            ->with('user')
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.clinics.dashboard', [
            'title' => 'داشبورد کلینیک',
            'user' => $user,
            'dashNotifications' => $dashNotifications,
            'activeVisits' => $activeVisits,
            'pendingVisits' => $pendingVisits,
            'clinicIncome' => (int) $clinicIncome,
            'recentBookings' => $recentBookings,
            'financeChart' => DashboardFinance::monthlySeries($user),
            'roleLabel' => $user->primaryRoleLabel(),
        ]);
    }

    private function coachDashboard(User $user, $dashNotifications): View
    {
        $todaysAppointments = Booking::query()
            ->where('provider_id', $user->id)
            ->whereDate('appointment_date', Carbon::today())
            ->with('user')
            ->orderBy('appointment_date')
            ->get();

        $pendingBookings = Booking::query()
            ->where('provider_id', $user->id)
            ->whereIn('status', ['PENDING', 'OPEN', 'QUOTED'])
            ->count();

        $teacherCourses = Course::where('user_id', $user->id)->get();
        $coachProducts = Product::where('user_id', $user->id)->get();

        $upcoming = Booking::query()
            ->where('provider_id', $user->id)
            ->whereIn('status', ['PENDING', 'CONFIRMED', 'OPEN', 'QUOTED'])
            ->with('user')
            ->orderByRaw('COALESCE(appointment_date, preferred_date, booking_date) asc')
            ->limit(8)
            ->get();

        return view('coaches.dashboard', [
            'title' => 'داشبورد مربی',
            'user' => $user,
            'dashNotifications' => $dashNotifications,
            'teacherCourses' => $teacherCourses,
            'todaysAppointments' => $todaysAppointments,
            'coachProducts' => $coachProducts,
            'coachId' => $user->id,
            'pendingBookings' => $pendingBookings,
            'upcoming' => $upcoming,
            'financeChart' => DashboardFinance::monthlySeries($user),
            'roleLabel' => $user->primaryRoleLabel(),
        ]);
    }

    private function breederDashboard(User $user, $dashNotifications): View
    {
        $dogsQuery = Dog::query()->where('user_id', $user->id);

        $readyDogs = (clone $dogsQuery)->where('status', 'ready')->count();
        $totalDogs = (clone $dogsQuery)->count();
        $pendingDogs = (clone $dogsQuery)->where('status', 'pending')->count();
        $soldDogs = (clone $dogsQuery)->where('status', 'sold')->count();
        $waitingDogs = (clone $dogsQuery)->where('status', 'waiting')->count();

        $pendingBookings = Booking::query()
            ->whereRaw('LOWER(service_type) = ?', ['breeder'])
            ->where('provider_id', $user->id)
            ->whereIn('status', ['PENDING', 'OPEN', 'QUOTED'])
            ->count();

        $openBookings = Booking::query()
            ->whereRaw('LOWER(service_type) = ?', ['breeder'])
            ->where('provider_id', $user->id)
            ->where('status', 'CONFIRMED')
            ->count();

        $recentDogs = (clone $dogsQuery)->latest()->limit(6)->get();
        $walletBalance = (int) ($user->wallet ?? 0);

        return view('breeders.dashboard', [
            'title' => 'داشبورد پرورش‌دهنده',
            'user' => $user,
            'dashNotifications' => $dashNotifications,
            'readyDogs' => $readyDogs,
            'totalDogs' => $totalDogs,
            'pendingDogs' => $pendingDogs,
            'soldDogs' => $soldDogs,
            'waitingDogs' => $waitingDogs,
            'pendingBookings' => $pendingBookings,
            'openBookings' => $openBookings,
            'recentDogs' => $recentDogs,
            'walletBalance' => $walletBalance,
            'financeChart' => DashboardFinance::monthlySeries($user),
            'roleLabel' => $user->primaryRoleLabel(),
        ]);
    }

    private function adminDashboard(User $user, $dashNotifications): View
    {
        $stats = [
            'users' => User::count(),
            'transactions' => Transaction::where('status', 'completed')->count(),
            'courses' => Course::where('is_active', 1)->count(),
            'dogs' => Dog::count(),
        ];

        $months = collect(range(0, 5))->map(fn ($i) => now()->subMonths($i)->startOfMonth())->reverse();

        $chart = [
            'labels' => $months->map(fn ($m) => $m->format('Y-m'))->toArray(),
            'data' => $months->map(fn ($m) => Transaction::where('status', 'completed')
                ->whereBetween('created_at', [$m, $m->copy()->endOfMonth()])
                ->sum('amount'))->toArray(),
        ];

        $recentTransactions = Transaction::with('user')->latest()->take(10)->get();

        return view('admin.dashboard.index', [
            'title' => 'داشبورد ادمین',
            'user' => $user,
            'notifications' => $dashNotifications,
            'stats' => $stats,
            'chart' => $chart,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
