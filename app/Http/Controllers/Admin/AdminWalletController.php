<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Support\DashboardFinance;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminWalletController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $transactions = Transaction::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        $walletBalance = (int) ($user->wallet ?? 0);

        $monthlyIncome = (int) $user->transactions()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('amount', '>', 0)
            ->sum('amount');

        $monthlyExpense = (int) abs($user->transactions()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('amount', '<', 0)
            ->sum('amount'));

        $netProfit = $monthlyIncome - $monthlyExpense;
        $financeChart = DashboardFinance::monthlySeries($user);

        return view('wallet.index', [
            'user' => $user,
            'transactions' => $transactions,
            'walletBalance' => $walletBalance,
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpense' => $monthlyExpense,
            'netProfit' => $netProfit,
            'financeChart' => $financeChart,
            'roleLabel' => method_exists($user, 'primaryRoleLabel') ? $user->primaryRoleLabel() : 'حساب',
            'backUrl' => $user->dashboardRoute(),
        ]);
    }
}
