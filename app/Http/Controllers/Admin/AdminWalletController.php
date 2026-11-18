<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class AdminWalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $transactions = Transaction::where('user_id', $user->id)->latest()->paginate(20);

        $walletBalance = $user->wallet;

        $monthlyIncome = $user->transactions()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->where('amount', '>', 0)
            ->sum('amount');

        $monthlyExpense = $user->transactions()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->where('amount', '<', 0)
            ->sum('amount');

        $netProfit = $monthlyIncome + $monthlyExpense;
        $monthlyIncomeChart = [85000, 30000, 20000, 60000, 55000, 80000, 45000, 95000, 10000, 0, 0, 0];

        return view('wallet.index', compact(
            'transactions',
            'walletBalance',
            'monthlyIncome',
            'monthlyExpense',
            'netProfit',
            'monthlyIncomeChart'
        ));
    }
}
