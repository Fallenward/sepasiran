<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminWalletController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // Get only this user's transactions
        $transactions = Transaction::where('user_id', $user->id)->latest()->get();
        // Wallet balance (from user)
        $walletBalance = $user->wallet;
        // Monthly income & expense
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
        $monthlyIncomeChart = [85000, 30000, 20000, 60000, 55000, 80000, 45000, 95000, 10000, 0, 0, 0]; // Replace with real DB logic

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
