<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Clinic;
use App\Models\Specialty;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all cities and specialties for filters
        $cities = City::all();
        $specialties = Specialty::all();

        // Build the base query with relationships
        $query = Clinic::with(['city', 'specialty']);

        // Apply city filter
        if ($request->has('city') && is_array($request->input('city'))) {
            $query->whereIn('city_id', $request->input('city'));
        }

        // Apply specialty filter
        if ($request->has('specialty') && is_array($request->input('specialty'))) {
            $query->whereIn('specialty_id', $request->input('specialty'));
        }

        // Paginate results
        $clinics = $query->paginate(9);

        // Append filters to pagination links to retain them
        $clinics->appends($request->only(['city', 'specialty']));

        // Prepare filters array for component
        $filters = [
            'city' => $cities,
            'specialty' => $specialties,
        ];

        // Return the view with clinics and filters
        return view('clinics.index', compact('clinics', 'filters'));
    }
    public function clinicwallet(){

        $clinic = Auth::user(); // assuming the authenticated user is the clinic
        $transactions = Transaction::where('user_id', $clinic->id)->latest()->get();

        $walletBalance = $transactions->sum('amount');

        // Example monthly income and expenses for current month
        $currentMonth = now()->month;

        $monthlyIncome = $transactions
            ->where('amount', '>', 0)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        $monthlyExpense = $transactions
            ->where('amount', '<', 0)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        $netProfit = $monthlyIncome + $monthlyExpense;

        return view('admin.clinics.wallet-balance', [
            'transactions' => $transactions,
            'walletBalance' => $walletBalance,
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpense' => abs($monthlyExpense),
            'netProfit' => $netProfit,
        ]);

    }


        public function dashboard()
    {
        $clinics = User::all();

        return view('admin.clinics.dashboard', compact('clinics'));
    }

}
