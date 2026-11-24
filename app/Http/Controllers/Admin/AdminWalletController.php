<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Support\DashboardFinance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

    public function deposit(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:10000', 'max:500000000'],
        ], [
            'amount.required' => 'مبلغ افزایش موجودی الزامی است.',
            'amount.min' => 'حداقل مبلغ ۱۰٬۰۰۰ تومان است.',
        ]);

        $amount = (int) $data['amount'];

        DB::transaction(function () use ($user, $amount) {
            Transaction::query()->create([
                'user_id' => $user->id,
                'transaction_id' => 'WLT-'.Str::upper(Str::random(12)),
                'description' => 'افزایش موجودی کیف پول',
                'status' => 'completed',
                'amount' => $amount,
                'base_price' => $amount,
                'payment_method' => 'online',
            ]);

            $user->increment('wallet', $amount);
        });

        return redirect()
            ->route('wallet.index')
            ->with('success', 'موجودی کیف پول با موفقیت افزایش یافت.');
    }
}
