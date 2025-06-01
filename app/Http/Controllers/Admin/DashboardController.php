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

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get(['message', 'created_at'])
            ->map(fn($note) => [
                'message' => $note->message,
                'date' => $note->created_at->format('Y/m/d'),
            ]);

        // Fetch user role - assuming user has only one role for dashboard
        $role = $user->roles->first()?->name;

        if (! $role) {
            abort(403, 'Access denied. Your role does not have a dashboard.');
        }

        switch ($role) {
            case 'clinic':
                $title = 'داشبورد کلینیک';
                $clinics = collect([$user]);

                $activeVisits = Booking::where([
                    ['provider_id', $user->id],
                    ['service_type', 'CLINIC'],
                    ['status', 'CONFIRMED'],
                ])->count();

                $clinicIncome = Booking::where([
                    ['provider_id', $user->id],
                    ['service_type', 'CLINIC'],
                    ['status', 'COMPLETED'],
                ])->sum('price');

                $activeDoctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))
                    ->whereHas('bookings', fn($q) => $q->where('provider_id', $user->id)->where('service_type', 'CLINIC'))
                    ->count();

                return view('admin.clinics.dashboard', compact(
                    'title', 'user', 'clinics', 'notifications',
                    'activeVisits', 'clinicIncome', 'activeDoctors'
                ));

            case 'coach':
                $title = 'داشبورد مربی';

                $teacherCourses = Course::where('user_id', $user->id)->get();
                $todaysAppointments = Booking::where('provider_id', $user->id)
                    ->whereDate('appointment_date', Carbon::today())
                    ->with('user') // eager loading
                    ->get();
                $coachProducts = Product::where('user_id', $user->id)->get();
                $coachId = $user->id;

                return view('coaches.dashboard', compact(
                    'title', 'user', 'notifications', 'teacherCourses', 'todaysAppointments', 'coachProducts', 'coachId'
                ));

            case 'breeder':
                $title = 'داشبورد پرورش‌دهنده';

                $teacherCourses = Course::where('user_id', $user->id)->get();

                $readyDogs = Dog::where('status', 'ready')->count();
                $totalDogs = Dog::count();
                $pendingDogs = Dog::where('status', 'pending')->count();
                $soldDogs = Dog::where('status', 'sold')->count();
                $waitingDogs = Dog::where('status', 'waiting')->count();

                $pendingBookings = Booking::where([
                    ['service_type', 'BREEDER'],
                    ['provider_id', $user->id],
                    ['status', 'PENDING']
                ])->count();

                $openBookings = Booking::where([
                    ['service_type', 'BREEDER'],
                    ['provider_id', $user->id],
                    ['status', 'CONFIRMED']
                ])->count();

                $clinicIncome = $user->transactions()->sum('amount') ?? 0;

                $activeDoctors = User::role('doctor')->where('status', 'active')->count();

                return view('breeders.dashboard', compact(
                    'title', 'user', 'notifications', 'teacherCourses',
                    'readyDogs', 'totalDogs', 'pendingDogs', 'soldDogs', 'waitingDogs',
                    'pendingBookings', 'openBookings', 'activeDoctors', 'clinicIncome'
                ));

            case 'admin':
                $title = 'داشبورد ادمین';

                $stats = [
                    'users' => User::count(),
                    'transactions' => Transaction::where('status', 'completed')->count(),
                    'courses' => Course::where('is_active', 1)->count(),
                    'dogs' => Dog::count(),
                ];

                $months = collect(range(0, 5))->map(fn($i) => now()->subMonths($i)->startOfMonth())->reverse();

                $chart = [
                    'labels' => $months->map(fn($m) => $m->format('Y-m'))->toArray(),
                    'data' => $months->map(fn($m) => Transaction::where('status', 'completed')
                        ->whereBetween('created_at', [$m, $m->copy()->endOfMonth()])
                        ->sum('amount'))->toArray(),
                ];

                $recentTransactions = Transaction::with('user')->latest()->take(10)->get();

                return view('admin.dashboard.index', compact(
                    'title', 'user', 'notifications', 'stats', 'chart', 'recentTransactions'
                ));

            default:
                abort(403, 'Access denied. Your role does not have a dashboard.');
        }
    }
}
