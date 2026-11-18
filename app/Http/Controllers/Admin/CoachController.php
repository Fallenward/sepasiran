<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\City;
use App\Models\Coach;
use App\Models\Dog;
use App\Models\Product;
use App\Models\Specialty;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    // Base query for coaches
$query = \App\Models\User::select('users.*')
    ->join('role_user', 'users.id', '=', 'role_user.user_id')
    ->join('roles', 'roles.id', '=', 'role_user.role_id')
    ->where('roles.name', 'coach')
    ->with(['city', 'speciality']);

    if ($request->filled('city')) {
        $query->whereIn('city_id', (array)$request->input('city'));
    }

    if ($request->filled('specialty')) {
        $query->whereIn('specialty_id', (array)$request->input('specialty'));
    }

    $coaches = $query->paginate(9);

    $filters = [
        'city' => $cities,
        'breed' => $breeds,
        'specialty' => $specialties,
    ];

    return view('coaches.index', compact('coaches', 'filters'));
}

    // New showById method
public function showById($id)
{
    $trainer = \App\Models\User::select('users.*')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->where('roles.name', 'coach')
        ->where('users.id', $id) // ✅ Filter by ID
        ->with(['city', 'speciality']) // ✅ Fix typo (speciality → specialty if that's your model name)
        ->firstOrFail(); // ✅ Get the actual model, not a query builder

    $dogs = \App\Models\Dog::where('user_id', $id)->get();

    $medals = [
        ['title' => 'رتبه اول آموزش', 'icon' => 'medal1.png'],
        ['title' => 'بیشترین فروش', 'icon' => 'medal2.png'],
        ['title' => 'مربی برتر سال', 'icon' => 'medal3.png'],
        ['title' => 'مربی خلاق', 'icon' => 'medal4.png'],
        ['title' => 'پیشنهاد کاربران', 'icon' => 'medal5.png'],
    ];

    $comments=['comment1' => 'inyekk comment ast',
                'comment2' => 'inyekk comment ast'];

    return view('coaches.show', compact('trainer', 'dogs', 'medals', 'comments'));
}




        public function editProfile()
        {
            $users = auth()->user();
            $breeds = Breed::all();

            return view('coaches.profile', compact('users', 'breeds'));
        }


        public function updateProfile(Request $request)
        {
            $user = auth()->user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'family' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'national_id' => 'nullable|string|max:20',
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|confirmed|min:6',

                'breeder_name' => 'nullable|string|max:255',
                'business_address' => 'nullable|string|max:255',
                'business_phone' => 'nullable|string|max:20',
                'breeder_type' => 'nullable|string|max:255',
                'bank_info' => 'nullable|string|max:255',
            ]);

            $user->fill($validated);

            if (!empty($validated['password'])) {
                $user->password = bcrypt($validated['password']);
            }

            $user->save();

            return redirect()->route('profile.edit')->with('success', 'اطلاعات با موفقیت بروزرسانی شد');
        }

public function listing(Request $request, $coachId)
{
    $coach = User::whereHas('roles', function ($q) {
        $q->where('name', 'coach');
    })->findOrFail($coachId);

    $query = Product::where('user_id', $coach->id);

    if ($request->filled('city')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('city_id', $request->city);
        });
    }

    $products = $query->with('user.city')->paginate(15);
    $cities = City::all();

    return view('coaches.listing', compact('products', 'cities', 'coach'));
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
public function storeList(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'family' => 'nullable|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'city_id' => 'required|exists:cities,id',
        'breed_id' => 'required|exists:breeds,id',
        'specialty_id' => 'required|exists:specialties,id',
        'description' => 'nullable|string',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'family' => $validated['family'] ?? null,
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'city_id' => $validated['city_id'],
        'breed_id' => $validated['breed_id'],
        'specialty_id' => $validated['specialty_id'],
        'bio' => $validated['description'] ?? null,
        'is_admin' => false,
    ]);

    // Attach coach role
    $coachRoleId = DB::table('roles')->where('name', 'coach')->value('id');
    \DB::table('role_user')->insert([
        'user_id' => $user->id,
        'role_id' => $coachRoleId,
    ]);

    return redirect()->route('coaches.index')->with('success', 'Coach created successfully');
}

    // Delete a coach
public function destroyList($id)
{
    $user = User::findOrFail($id);

    // Optional: check if user has coach role before deleting
    if (!$user->roles()->where('name', 'coach')->exists()) {
        return redirect()->route('coaches.index')->with('error', 'This user is not a coach.');
    }

    $user->delete();

    return redirect()->route('coaches.index')->with('success', 'Coach deleted successfully');
}


    public function coachWallet()
{
    $userId = auth()->id();
    $baseQuery = Transaction::where('user_id', $userId);

    $walletBalance = (clone $baseQuery)->where('status', 'completed')->sum('amount');

    $monthlyIncome = (clone $baseQuery)
        ->whereMonth('created_at', now()->month)
        ->where('amount', '>', 0)
        ->sum('amount');

    $monthlyExpense = (clone $baseQuery)
        ->whereMonth('created_at', now()->month)
        ->where('amount', '<', 0)
        ->sum('amount');

    $netProfit = $monthlyIncome + $monthlyExpense;
    $transactions = $baseQuery->latest()->paginate(20);

    return view('admin.coaches.wallet-balance', [
        'transactions' => $transactions,
        'walletBalance' => $walletBalance,
        'monthlyIncome' => $monthlyIncome,
        'monthlyExpense' => abs($monthlyExpense),
        'netProfit' => $netProfit,
    ]);
}

}



