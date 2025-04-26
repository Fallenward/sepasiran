<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Course;
use App\Models\Dog;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $user || ! $user->is_admin) {
            return redirect()
                ->route('login')
                ->with('error', 'دسترسی غیرمجاز. لطفاً وارد شوید.');
        }

        /* ---------- آمار کارت‌ها ---------- */
        $stats = [
            'users'        => User::count(),
            'transactions' => Transaction::where('status', 'completed')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->count(),
            'courses'      => Course::where('is_active', 1)->count(),
            'dogs'         => Dog::count(),
        ];

        /* ---------- داده‌های نمودار فروش (۶ ماه اخیر) ---------- */
        $months = collect(range(0, 5))->map(function ($i) {
            return Carbon::now()->subMonths($i)->startOfMonth();
        })->reverse(); // ترتیب از قدیم به جدید

        $chart = [
            'labels' => $months->map(fn ($m) => $m->format('Y-m'))->toArray(),
            'data'   => $months->map(function ($m) {
                return Transaction::where('status', 'completed')
                    ->whereBetween('created_at', [$m, $m->copy()->endOfMonth()])
                    ->sum('amount');
            })->toArray(),
        ];

        /* ---------- آخرین تراکنش‌ها ---------- */
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view(
            'admin.dashboard.index',
            compact('stats', 'chart', 'recentTransactions')
        );
    }
}
