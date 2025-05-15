<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\City;
use App\Models\Coach;
use App\Models\Dog;
use App\Models\Specialty;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = Coach::with(['city', 'breed', 'specialty'])->paginate(10);
        return view('admin.coaches.index', compact('coaches'));
    }

    public function create()
    {
        $cities = City::all();
        $breeds = Breed::all();
        $specialties = Specialty::all();
        return view('admin.coaches.create', compact('cities', 'breeds', 'specialties'));
    }

    public function store(Request $request)
    {
        // اعتبارسنجی ورودی‌ها
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id', // اطمینان از اینکه city_id در جدول cities موجود است
            'breed_id' => 'required|exists:breeds,id', // اطمینان از اینکه breed_id در جدول breeds موجود است
            'specialty_id' => 'required|exists:specialties,id', // اطمینان از اینکه specialty_id در جدول specialties موجود است
            'description' => 'required|string',
        ]);

        // ذخیره داده‌ها
        $coach = new Coach();
        $coach->name = $validated['name'];
        $coach->city_id = $validated['city_id'];
        $coach->breed_id = $validated['breed_id'];
        $coach->specialty_id = $validated['specialty_id'];
        $coach->description = $validated['description'];
        $coach->save();

        // بازگشت به صفحه اصلی با پیام موفقیت
        return redirect()->route('admin.coaches.index')->with('success', 'Coach created successfully');
    }


    public function show($id)
    {
        $coach = Coach::findOrFail($id);
        return view('admin.coaches.show', compact('coach'));
    }

    public function edit($id)
    {
        $coach = Coach::findOrFail($id);
        $cities = City::all();
        $breeds = Breed::all();
        $specialties = Specialty::all();
        return view('admin.coaches.edit', compact('coach', 'cities', 'breeds', 'specialties'));
    }

    public function update(Request $request, $id)
    {
        // اعتبارسنجی ورودی‌ها
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'breed_id' => 'required|exists:breeds,id',
            'specialty_id' => 'required|exists:specialties,id',
            'description' => 'required|string',
        ]);

        // پیدا کردن مربی با شناسه $id
        $coach = Coach::findOrFail($id);

        // به‌روزرسانی داده‌ها
        $coach->name = $validated['name'];
        $coach->city_id = $validated['city_id'];
        $coach->breed_id = $validated['breed_id'];
        $coach->specialty_id = $validated['specialty_id'];
        $coach->description = $validated['description'];
        $coach->save();

        // بازگشت به صفحه اصلی با پیام موفقیت
        return redirect()->route('admin.coaches.index')->with('success', 'Coach updated successfully');
    }


    public function destroy($id)
    {
        $coach = Coach::findOrFail($id);
        $coach->delete();
        return redirect()->route('admin.coaches.index')->with('success', 'Coach deleted successfully');
    }



public function indexFront(Request $request)
{
    $cities = City::all();
    $breeds = Breed::all();
    $specialties = Specialty::all();

    $query = Coach::with(['user', 'city', 'breed', 'specialty'])
        ->whereHas('user', function ($q) {
            $q->where('role', 'coach');
        });

    if ($request->filled('city')) {
        $query->whereIn('city_id', $request->input('city'));
    }

    if ($request->filled('breed')) {
        $query->whereIn('breed_id', $request->input('breed'));
    }

    if ($request->filled('specialty')) {
        $query->whereIn('specialty_id', $request->input('specialty'));
    }

    $coaches = $query->paginate(9);

    if ($request->ajax()) {
        $html = view('coaches.partials.coach-cards', compact('coaches'))->render();
        return response()->json([
            'html' => $html,
            'hasMore' => $coaches->hasMorePages(),
            'nextPage' => $coaches->currentPage() + 1,
        ]);
    }

    $filters = [
        'city' => $cities,
        'breed' => $breeds,
        'specialty' => $specialties,
    ];

  return view('coaches.index', compact('coaches', 'filters'));
}

public function dashboard()

    {
        // Get the authenticated user and any necessary data
        $users = User::all();
        // Example: You can fetch a count of dogs or any other relevant data
        $dogsCount = Dog::count();  // You can fetch dogs that belong to this coach if applicable

        return view('coaches.dashboard', compact('users', 'dogsCount'));
    }


        public function editProfile()
    {
        $users = User::all();
        $breeds = Breed::all();

        return view('coaches.profile', compact('users', 'breeds'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth()->user;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'national_id'=> 'nullable|string|max:10',
            'phone'      => 'nullable|string|max:11',
            'password'   => 'nullable|string|min:6|confirmed',

            'breeder_name'     => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:255',
            'business_phone'   => 'nullable|string|max:11',
            'breeder_type'     => 'nullable|string|max:255',
            'bank_info'        => 'nullable|string|max:255',
        ]);

        // Update fields
        $user->fill($validated);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('coach.profile.edit')->with('success', 'پروفایل با موفقیت به‌روزرسانی شد.');
    }

        public function listing(Request $request)
    {
        $query = Coach::query();

        // Filter by breed, gender, and other parameters
        if ($request->has('breed')) {
            $query->where('breed_id', $request->breed);
        }

        if ($request->has('city')) {
            $query->where('city_id', $request->city);
        }

        if ($request->has('specialty')) {
            $query->where('specialty_id', $request->specialty);
        }

        $coaches = $query->get();

        $breeds = Breed::all();
        $cities = City::all();
        $specialties = Specialty::all();

        return view('coaches.listing', compact('coaches', 'breeds', 'cities', 'specialties'));
    }

    // Show form to create a new coach
    public function createList()
    {
        $breeds = Breed::all();
        $cities = City::all();
        $specialties = Specialty::all();

        return view('admin.coaches.create', compact('breeds', 'cities', 'specialties'));
    }

    // Store a new coach in the database
    public function storelist(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'breed_id' => 'required|exists:breeds,id',
            'specialty_id' => 'required|exists:specialties,id',
            'description' => 'required|string',
        ]);

        Coach::create($request->all());

        return redirect()->route('coaches.index')->with('success', 'Coach created successfully');
    }

    // Delete a coach
    public function destroyList($id)
    {
        $coach = Coach::findOrFail($id);
        $coach->delete();

        return redirect()->route('coaches.index')->with('success', 'Coach deleted successfully');
    }

    public function coachWallet()
{
    $userId = auth()->id();

    $transactions = Transaction::where('user_id', $userId)->latest()->get();

    $walletBalance = $transactions->where('status', 'completed')->sum('amount');

    $monthlyTransactions = $transactions->filter(function ($transaction) {
        return $transaction->created_at->isCurrentMonth();
    });

    $monthlyIncome = $monthlyTransactions->where('amount', '>', 0)->sum('amount');
    $monthlyExpense = $monthlyTransactions->where('amount', '<', 0)->sum('amount');
    $netProfit = $monthlyIncome + $monthlyExpense;

    return view('admin.coaches.wallet-balance', [
        'transactions' => $transactions,
        'walletBalance' => $walletBalance,
        'monthlyIncome' => $monthlyIncome,
        'monthlyExpense' => abs($monthlyExpense),
        'netProfit' => $netProfit,
    ]);
}

}



