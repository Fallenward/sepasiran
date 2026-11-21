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
    $filters = [
        'city' => City::orderBy('name')->get(['id', 'name']),
        'breed' => Breed::orderBy('name')->get(['id', 'name']),
        'size' => [
            ['id' => 'small', 'name' => 'کوچک'],
            ['id' => 'medium', 'name' => 'متوسط'],
            ['id' => 'large', 'name' => 'بزرگ'],
        ],
    ];

    $query = \App\Models\User::select('users.*')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->where('roles.name', 'breeder')
        ->with(['city', 'speciality']);

    if ($request->filled('q')) {
        $term = '%' . trim((string) $request->q) . '%';
        $query->where(function ($builder) use ($term) {
            $builder->where('users.name', 'like', $term)
                ->orWhere('users.family', 'like', $term)
                ->orWhere('users.bio', 'like', $term)
                ->orWhereHas('city', fn ($q) => $q->where('name', 'like', $term));
        });
    }

    if ($request->filled('city')) {
        $query->whereIn('users.city_id', (array) $request->input('city'));
    }

    if ($request->filled('breed')) {
        $query->whereIn('users.breed_id', (array) $request->input('breed'));
    }

    if ($request->filled('size')) {
        $query->whereIn('users.size', (array) $request->input('size'));
    }

    $breeders = $query->paginate(9)->appends($request->query());

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

    return redirect()->route('breeder.dashboard')->with('success', 'سگ با موفقیت ثبت شد.');
}



public function show($id)
{
    $trainer = \App\Models\User::query()
        ->where('users.id', $id)
        ->whereHas('roles', fn ($q) => $q->where('name', 'breeder'))
        ->with(['speciality'])
        ->firstOrFail();

    $cityName = null;
    if (! empty($trainer->city_id)) {
        $cityName = optional(\App\Models\City::query()->find($trainer->city_id))->name;
    }

    $dogsQuery = \App\Models\Dog::query()->where('user_id', $id);
    $dogsCount = (clone $dogsQuery)->count();
    $dogs = $dogsQuery->with('breed')->latest()->take(12)->get();

    $specialties = $trainer->speciality ?? collect();
    $address = trim((string) ($trainer->address ?? ''));

    $facts = array_values(array_filter([
        $cityName ? ['label' => 'شهر', 'value' => $cityName] : null,
        ['label' => 'آگهی سگ', 'value' => (string) $dogsCount, 'num' => true],
        ['label' => 'تخصص‌ها', 'value' => (string) $specialties->count(), 'num' => true],
        $address !== '' ? ['label' => 'آدرس', 'value' => \Illuminate\Support\Str::limit($address, 40)] : null,
    ]));

    $bookUrl = route('frontend.booking.index', array_filter([
        'type' => 'breeder',
        'provider' => (int) $trainer->id,
        'city_id' => $trainer->city_id ?: null,
    ]));

    return view('breeders.show', [
        'trainer' => $trainer,
        'dogs' => $dogs,
        'specialties' => $specialties,
        'cityName' => $cityName,
        'facts' => $facts,
        'bookUrl' => $bookUrl,
    ]);
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
