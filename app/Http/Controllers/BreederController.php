<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Breed;
use App\Models\Breeders;
use App\Models\City;
use App\Models\Dog;
use App\Models\DogOrder;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BreederController extends Controller
{
public function index(Request $request)
{

    \Illuminate\Support\Facades\DB::listen(function ($query) {
    logger($query->sql);
    logger($query->bindings);
});
    // Sidebar filters
    $filters = [
        'cities' => City::orderBy('name')->get(),
        'breeds' => Breed::orderBy('name')->get(),
        'sizes' => [
            'mini' => 'کوچک (Mini)',
            'medium' => 'متوسط (Medium)',
            'large' => 'بزرگ (Large)',
        ],
    ];

    // Base query for breeders
    $query = \App\Models\User::select('users.*')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->where('roles.name', 'breeder')
        ->with(['city', 'speciality']);

    // ✅ Filter: city (city_id)
    if ($request->has('city')) {
        $query->whereIn('users.city_id', (array) $request->input('city'));
    }

    // ✅ Filter: breed (adjust column if different)
    if ($request->has('breed')) {
        $query->whereIn('users.breed_id', (array) $request->input('breed'));
    }

    // ✅ Filter: size
    if ($request->has('size')) {
        $query->whereIn('users.size', (array) $request->input('size'));
    }

    // Paginate results
    $breeders = $query->paginate(12);

    return view('breeders.index', compact('filters', 'breeders'));
}


    public function dashboard()
{
    $users = User::all(); // Get the currently authenticated user


    $readyDogs = Dog::where('status', 'ready')->count();
    $totalDogs = Dog::count();
    $pendingDogs = Dog::where('status', 'pending')->count();
    $soldDogs = Dog::where('status', 'sold')->count();
    $waitingDogs = Dog::where('status', 'waiting')->count();

    $pendingOrders = Booking::where('status', 'pending')->count();
    $totalRevenue = booking::where('status', 'paid')->sum('total_price');
    $openOrders = booking::where('status', 'open')->count();

    return view('breeders.dashboard', compact(
        'readyDogs',
        'users',
        'totalDogs',
        'pendingDogs',
        'soldDogs',
        'waitingDogs',
        'pendingOrders',
        'totalRevenue',
        'openOrders'
    ));
}

   public function profile(Request $request)
    {
        $user = $request->user();
        $breeds = Breed::all();

        return view('breeders.profile', compact('user', 'breeds'));
    }

    // Handle profile update
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Validate request data
        $validated = $request->validate([
            'first_name'       => 'nullable|string|max:255',
            'last_name'        => 'nullable|string|max:255',
            'email'            => 'nullable|email|max:255',
            'national_id'      => 'nullable|string|max:20',
            'phone'            => 'nullable|string|max:20',
            'password'         => 'nullable|string|min:6|confirmed',
            'breeder_name'     => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:255',
            'business_phone'   => 'nullable|string|max:20',
            'breeder_type'     => 'nullable|string|max:255',
            'bank_info'        => 'nullable|string|max:255',
        ]);

        // Update fields
        $user->fill($validated);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->back()->with('success', 'اطلاعات با موفقیت به‌روزرسانی شد.');

}
public function dogsListing(Request $request)
{
    $user = $request->user();


    $dogs = Dog::with('breed')->where('user_id', 1)->get();

    return view('breeders.dogs-listing', compact('dogs'));
}

public function create()
{
    // You might want to fetch breeds to show in a dropdown
    $breeds = Breed::all(); // if you have a `breeds` table
    return view('breeders.dashboard-create', compact('breeds'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'title' => 'nullable|string|max:255',
        'color' => 'nullable|string|max:255',
        'status' => 'nullable|string|max:255',
        'age' => 'nullable|integer',
        'birth_date' => 'nullable|date',
        'cover_type' => 'nullable|string|max:255',
        'eye_color' => 'nullable|string|max:255',
        'shajare' => 'nullable|boolean',
        'breed_id' => 'nullable|exists:breeds,id',

        'father_name' => 'nullable|string|max:255',
        'father_breed_id' => 'nullable|exists:breeds,id',

        'mother_name' => 'nullable|string|max:255',
        'mother_breed_id' => 'nullable|exists:breeds,id',

        'maternal_grandmother_name' => 'nullable|string|max:255',
        'maternal_grandmother_breed_id' => 'nullable|exists:breeds,id',

        'maternal_grandfather_name' => 'nullable|string|max:255',
        'maternal_grandfather_breed_id' => 'nullable|exists:breeds,id',

        'paternal_grandmother_name' => 'nullable|string|max:255',
        'paternal_grandmother_breed_id' => 'nullable|exists:breeds,id',

        'paternal_grandfather_name' => 'nullable|string|max:255',
        'paternal_grandfather_breed_id' => 'nullable|exists:breeds,id',
    ]);

    $data['user_id'] = auth()->id();

    Dog::create($data);

    return redirect()->route('breeders.dashboard')->with('success', 'Dog added successfully!');
}


public function wallet()
{
    // Get the authenticated user (or you can specify a specific user)
    $userId = auth()->id();

    // Fetch all transactions for the user (you may add pagination later for performance)
    $transactions = Transaction::where('user_id', $userId)->latest()->get();

    // Calculate wallet balance: sum of 'completed' transactions' amounts
    $walletBalance = $transactions->where('status', 'completed')->sum('amount');

    // Filter transactions for the current month
    $monthlyTransactions = $transactions->filter(function ($transaction) {
        return $transaction->created_at->isCurrentMonth(); // For current month
    });

    // Calculate monthly income (positive amounts)
    $monthlyIncome = $monthlyTransactions->where('amount', '>', 0)->sum('amount');

    // Calculate monthly expenses (negative amounts)
    $monthlyExpense = $monthlyTransactions->where('amount', '<', 0)->sum('amount');

    // Net profit for the month (Income - Expenses)
    $netProfit = $monthlyIncome + $monthlyExpense; // Since expenses are negative, adding is correct

    return view('breeders.wallet-balance', [
        'transactions' => $transactions,
        'walletBalance' => $walletBalance,
        'monthlyIncome' => $monthlyIncome,
        'monthlyExpense' => abs($monthlyExpense), // Making expense positive for display
        'netProfit' => $netProfit,
    ]);
}


public function show($id)
{
    $trainer = \App\Models\User::select('users.*')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->where('roles.name', 'breeder')
        ->where('users.id', $id)
        ->with(['city', 'speciality', 'dogs']) // assuming 'dogs' relationship exists
        ->firstOrFail();

    $dogsPending = $trainer->dogs()->where('status', 'pending')->get();
    $dogsReady = $trainer->dogs()->where('status', 'ready')->get();

    $medals = []; // your medals array
    $comments = [];

    return view('breeders.show', compact('trainer', 'dogsPending', 'dogsReady', 'medals', 'comments'));
}
}
