<?php

namespace App\Http\Controllers;


use App\Models\Breed;
use App\Models\City;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Dog;



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






public function dogsListing(Request $request)
{
    $user = $request->user();

    $dogs = Dog::with('breed')
        ->where('user_id', $user->id) // Only filter by existing column
        ->when($request->breed, fn($q) =>
            $q->whereHas('breed', fn($b) =>
                $b->where('slug', $request->breed)))
        ->when($request->gender, fn($q) =>
            $q->where('gender', $request->gender))
        ->when($request->age, fn($q) =>
            $q->where('age', $request->age))
        ->when($request->vaccine, function ($q) use ($request) {
            return $request->vaccine === 'done'
                ? $q->where('vaccinated', true)
                : $q->where('vaccinated', false);
        })
        ->when($request->sold !== null, fn($q) =>
            $q->where('sold', $request->sold))
        ->get();

    return view('breeders.dogs-listing', [
        'dogs' => $dogs,
        'breeder' => $user,
    ]);
}


public function create()
{
    $breeds = Breed::all();
    return view('breeders.dashboard-create', compact('breeds'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'color' => 'nullable|string|max:255',
        'status' => 'nullable|string',
        'age' => 'nullable|integer',
        'birth_date' => 'nullable|date',
        'cover_type' => 'nullable|string',
        'eye_color' => 'nullable|string',
        'shajare' => 'nullable|boolean',
        'breed_id' => 'nullable|exists:breeds,id',
        'father_name' => 'nullable|string',
        'father_breed_id' => 'nullable|exists:breeds,id',
        'mother_name' => 'nullable|string',
        'mother_breed_id' => 'nullable|exists:breeds,id',
        'maternal_grandmother_name' => 'nullable|string',
        'maternal_grandmother_breed_id' => 'nullable|exists:breeds,id',
        'maternal_grandfather_name' => 'nullable|string',
        'maternal_grandfather_breed_id' => 'nullable|exists:breeds,id',
        'paternal_grandmother_name' => 'nullable|string',
        'paternal_grandmother_breed_id' => 'nullable|exists:breeds,id',
        'paternal_grandfather_name' => 'nullable|string',
        'paternal_grandfather_breed_id' => 'nullable|exists:breeds,id',
    ]);

    $validated['user_id'] = auth()->id(); // Automatically assign the logged-in user

    Dog::create($validated);

    return redirect()->route('breeders.dashboard')->with('success', 'سگ با موفقیت ثبت شد.');
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

    // Example statistics (you can modify this dynamically)
    $statistics = [
        ['title' => 'سگ‌های آماده', 'counter' => $dogsReady->count()],
        ['title' => 'سگ‌های در انتظار', 'counter' => $dogsPending->count()],
        ['title' => 'تجربه (سال)', 'counter' => $trainer->experience_years ?? 0],
        ['title' => 'مشتریان راضی', 'counter' => 120], // Example static number
    ];

    $medals = [
        ['title' => 'مدال طلا', 'description' => 'بهترین مربی سال 2024', 'progress' => 80],
        ['title' => 'مدال نقره', 'description' => 'پرورش‌دهنده برتر منطقه', 'progress' => 60],
        ['title' => 'مدال برنز', 'description' => 'تلاش مداوم در آموزش سگ‌ها', 'progress' => 25],
        ['title' => 'مدال پلاتین', 'description' => 'رضایت مشتریان بالا', 'progress' => 90],
        ['title' => 'مدال الماس', 'description' => 'نوآوری در تربیت سگ‌ها', 'progress' => 95],
        ['title' => 'مدال یاقوت', 'description' => 'خدمات برجسته به جامعه', 'progress' => 50],
    ];


    $comments = [];

    return view('breeders.show', compact('trainer', 'dogsPending', 'dogsReady', 'medals', 'comments', 'statistics'));
}

    public function edit()
    {
        $user = auth()->user();
        $breeds = Breed::all();

        return view('breeders.profile', compact('user', 'breeds'));
    }

    // Handle form submission
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name'        => 'nullable|string|max:255',
            'last_name'         => 'nullable|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'national_id'       => 'nullable|string|max:20',
            'phone'             => 'nullable|string|max:20',
            'password'          => 'nullable|string|min:6|confirmed',

            // Breeder-specific fields
            'breeder_name'      => 'nullable|string|max:255',
            'business_address'  => 'nullable|string|max:255',
            'business_phone'    => 'nullable|string|max:20',
            'breeder_type'      => 'nullable|string|max:255',
            'bank_info'         => 'nullable|string|max:255',
        ]);

        // If password is filled, hash it. Otherwise don't update it.
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'پروفایل با موفقیت به‌روزرسانی شد.');
    }

}
