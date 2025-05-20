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
    $cities = City::all();
    $specialties = Specialty::all();

    // Fetch users with role 'clinic' and eager load city & specialty
$query = \App\Models\User::select('users.*')
    ->join('role_user', 'users.id', '=', 'role_user.user_id')
    ->join('roles', 'roles.id', '=', 'role_user.role_id')
    ->where('roles.name', 'clinic')
    ->with(['city', 'speciality']);

    // Filter by city
    if ($request->has('city') && is_array($request->input('city'))) {
        $query->whereIn('city_id', $request->input('city'));
    }

    // Filter by specialty
    if ($request->has('specialty') && is_array($request->input('specialty'))) {
        $query->whereIn('specialty_id', $request->input('specialty'));
    }

    $clinics = $query->paginate(9);
    $clinics->appends($request->only(['city', 'specialty']));

    $filters = [
        'city' => $cities,
        'specialty' => $specialties,
    ];

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
