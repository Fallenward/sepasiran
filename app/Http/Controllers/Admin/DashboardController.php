<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Dog;
use App\Models\Notification;
use App\Models\Product;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
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

        $myDogs = Dog::query()
            ->where('user_id', $user->id)
            ->with(['breed', 'media'])
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.clinics.dashboard', [
            'title' => 'داشبورد کلینیک',
            'user' => $user,
            'dashNotifications' => $dashNotifications,
            'activeVisits' => $activeVisits,
            'pendingVisits' => $pendingVisits,
            'clinicIncome' => (int) $clinicIncome,
            'recentBookings' => $recentBookings,
            'myDogs' => $myDogs,
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

        $myDogs = Dog::query()
            ->where('user_id', $user->id)
            ->with(['breed', 'media'])
            ->latest()
            ->limit(6)
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
            'myDogs' => $myDogs,
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
            'myDogs' => $recentDogs,
            'walletBalance' => $walletBalance,
            'financeChart' => DashboardFinance::monthlySeries($user),
            'roleLabel' => $user->primaryRoleLabel(),
        ]);
    }

    private function adminDashboard(User $user, $dashNotifications): View
    {
        $monthStarts = collect(range(0, 5))
            ->map(fn ($i) => now()->subMonths($i)->startOfMonth())
            ->reverse()
            ->values();

        $dayStarts = collect(range(0, 13))
            ->map(fn ($i) => now()->subDays($i)->startOfDay())
            ->reverse()
            ->values();

        $jalaliLabel = function ($carbon, string $format = 'Y/m') {
            try {
                return \Morilog\Jalali\Jalalian::fromCarbon($carbon)->format($format);
            } catch (\Throwable) {
                return $carbon->format($format === 'Y/m' ? 'Y-m' : 'm-d');
            }
        };

        $salesByMonth = $monthStarts->map(fn ($m) => (float) Transaction::query()
            ->whereIn('status', ['completed', 'paid', 'success'])
            ->where('amount', '>', 0)
            ->whereBetween('created_at', [$m, $m->copy()->endOfMonth()])
            ->sum('amount'));

        $usersByMonth = $monthStarts->map(fn ($m) => (int) User::query()
            ->whereBetween('created_at', [$m, $m->copy()->endOfMonth()])
            ->count());

        $bookingsByMonth = $monthStarts->map(fn ($m) => (int) Booking::query()
            ->whereBetween('created_at', [$m, $m->copy()->endOfMonth()])
            ->count());

        $salesByDay = $dayStarts->map(fn ($d) => (float) Transaction::query()
            ->whereIn('status', ['completed', 'paid', 'success'])
            ->where('amount', '>', 0)
            ->whereBetween('created_at', [$d, $d->copy()->endOfDay()])
            ->sum('amount'));

        $bookingStatus = Booking::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $dogStatus = Dog::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $ticketStatus = SupportTicket::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $serviceMix = Booking::query()
            ->selectRaw('service_type, COUNT(*) as total')
            ->whereNotNull('service_type')
            ->groupBy('service_type')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'service_type');

        $revenue30 = (float) Transaction::query()
            ->where('created_at', '>=', now()->subDays(30))
            ->whereIn('status', ['completed', 'paid', 'success'])
            ->where('amount', '>', 0)
            ->sum('amount');

        $revenuePrev30 = (float) Transaction::query()
            ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
            ->whereIn('status', ['completed', 'paid', 'success'])
            ->where('amount', '>', 0)
            ->sum('amount');

        $revenueDelta = $revenuePrev30 > 0
            ? round((($revenue30 - $revenuePrev30) / $revenuePrev30) * 100, 1)
            : ($revenue30 > 0 ? 100.0 : 0.0);

        $newUsers30 = (int) User::query()->where('created_at', '>=', now()->subDays(30))->count();
        $completedBookings = (int) Booking::query()->whereIn('status', ['COMPLETED', 'CONFIRMED', 'ACCEPTED'])->count();
        $bookingTotal = max(1, (int) Booking::query()->count());
        $bookingSuccessRate = round(($completedBookings / $bookingTotal) * 100, 1);

        $stats = [
            'users' => User::count(),
            'new_users_30' => $newUsers30,
            'transactions' => Transaction::query()
                ->where('created_at', '>=', now()->subDays(30))
                ->whereIn('status', ['completed', 'paid', 'success'])
                ->count(),
            'revenue_30' => $revenue30,
            'revenue_delta' => $revenueDelta,
            'courses' => Course::where('is_active', 1)->count(),
            'dogs' => Dog::count(),
            'dogs_available' => (int) ($dogStatus['available'] ?? 0) + (int) ($dogStatus['ready'] ?? 0),
            'open_tickets' => (int) ($ticketStatus['open'] ?? 0),
            'inbox' => SupportMessage::query()->count(),
            'pending_bookings' => Booking::query()->whereIn('status', ['PENDING', 'OPEN', 'QUOTED'])->count(),
            'booking_success_rate' => $bookingSuccessRate,
        ];

        $charts = [
            'sales' => [
                'labels' => $monthStarts->map(fn ($m) => $jalaliLabel($m, 'Y/m'))->toArray(),
                'data' => $salesByMonth->toArray(),
            ],
            'users' => [
                'labels' => $monthStarts->map(fn ($m) => $jalaliLabel($m, 'Y/m'))->toArray(),
                'data' => $usersByMonth->toArray(),
            ],
            'bookingsTrend' => [
                'labels' => $monthStarts->map(fn ($m) => $jalaliLabel($m, 'Y/m'))->toArray(),
                'data' => $bookingsByMonth->toArray(),
            ],
            'dailySales' => [
                'labels' => $dayStarts->map(fn ($d) => $jalaliLabel($d, 'm/d'))->toArray(),
                'data' => $salesByDay->toArray(),
            ],
            'bookingStatus' => [
                'labels' => $bookingStatus->keys()->values()->toArray(),
                'data' => $bookingStatus->values()->map(fn ($v) => (int) $v)->toArray(),
            ],
            'dogStatus' => [
                'labels' => $dogStatus->keys()->values()->toArray(),
                'data' => $dogStatus->values()->map(fn ($v) => (int) $v)->toArray(),
            ],
            'ticketStatus' => [
                'labels' => $ticketStatus->keys()->map(fn ($s) => [
                    'open' => 'باز',
                    'answered' => 'پاسخ‌داده‌شده',
                    'closed' => 'بسته',
                ][$s] ?? $s)->values()->toArray(),
                'data' => $ticketStatus->values()->map(fn ($v) => (int) $v)->toArray(),
            ],
            'serviceMix' => [
                'labels' => $serviceMix->keys()->values()->toArray(),
                'data' => $serviceMix->values()->map(fn ($v) => (int) $v)->toArray(),
            ],
        ];

        // Keep legacy key for older blade fragments / tests.
        $chart = $charts['sales'];

        $insights = [
            [
                'title' => 'درآمد ۳۰ روز',
                'value' => number_format((int) $revenue30).' ریال',
                'note' => ($revenueDelta >= 0 ? '+' : '').$revenueDelta.'٪ نسبت به ۳۰ روز قبل',
            ],
            [
                'title' => 'نرخ موفقیت رزرو',
                'value' => $bookingSuccessRate.'٪',
                'note' => number_format($completedBookings).' از '.number_format($bookingTotal).' رزرو',
            ],
            [
                'title' => 'سگ‌های قابل عرضه',
                'value' => number_format((int) $stats['dogs_available']),
                'note' => 'از مجموع '.number_format((int) $stats['dogs']).' آگهی',
            ],
            [
                'title' => 'تیکت باز',
                'value' => number_format((int) $stats['open_tickets']),
                'note' => 'نیاز به پاسخ ادمین',
            ],
        ];

        return view('admin.dashboard.index', [
            'title' => 'داشبورد ادمین',
            'user' => $user,
            'notifications' => $dashNotifications,
            'stats' => $stats,
            'chart' => $chart,
            'charts' => $charts,
            'insights' => $insights,
            'recentTransactions' => Transaction::with('user')->latest()->take(8)->get(),
            'recentTickets' => SupportTicket::query()->latest('last_message_at')->latest('id')->take(6)->get(),
            'recentMessages' => SupportMessage::query()->latest()->take(6)->get(),
        ]);
    }
}
