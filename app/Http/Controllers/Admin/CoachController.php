<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Course;


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
    public function index(Request $request)
    {
        $coaches = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'coach'))
            ->with(['city', 'roles'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . trim((string) $request->q) . '%';
                $query->where(function ($builder) use ($term) {
                    $builder->where('name', 'like', $term)
                        ->orWhere('family', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.coaches.index', compact('coaches'));
    }

    public function create()
    {
        $cities = City::orderBy('name')->get();
        $coach = new User();

        return view('admin.coaches.create', compact('cities', 'coach'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6|confirmed',
            'city_id' => 'nullable|exists:cities,id',
            'bio' => 'nullable|string',
        ]);

        $coach = User::create([
            'name' => $validated['name'],
            'family' => $validated['family'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'city_id' => $validated['city_id'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'is_admin' => false,
        ]);

        $coachRoleId = DB::table('roles')->where('name', 'coach')->value('id');
        if ($coachRoleId) {
            $coach->roles()->syncWithoutDetaching([$coachRoleId]);
        }

        return redirect()->route('admin.coaches.index')->with('success', 'مربی با موفقیت ایجاد شد.');
    }

    public function show($id)
    {
        $coach = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'coach'))
            ->with(['city', 'roles'])
            ->findOrFail($id);

        return view('admin.coaches.show', compact('coach'));
    }

    public function edit($id)
    {
        $coach = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'coach'))
            ->findOrFail($id);
        $cities = City::orderBy('name')->get();

        return view('admin.coaches.edit', compact('coach', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $coach = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'coach'))
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $coach->id,
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:6|confirmed',
            'city_id' => 'nullable|exists:cities,id',
            'bio' => 'nullable|string',
        ]);

        $coach->fill([
            'name' => $validated['name'],
            'family' => $validated['family'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        if (! empty($validated['password'])) {
            $coach->password = Hash::make($validated['password']);
        }

        $coach->save();

        return redirect()->route('admin.coaches.index')->with('success', 'مربی به‌روزرسانی شد.');
    }

    public function destroy($id)
    {
        $coach = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'coach'))
            ->findOrFail($id);

        $coachRoleId = DB::table('roles')->where('name', 'coach')->value('id');
        if ($coachRoleId) {
            $coach->roles()->detach($coachRoleId);
        }

        return redirect()->route('admin.coaches.index')->with('success', 'نقش مربی از کاربر برداشته شد.');
    }



public function indexFront(Request $request)
{
    $cities = City::orderBy('name')->get(['id', 'name']);
    $breeds = Breed::orderBy('name')->get(['id', 'name']);
    $specialties = Specialty::orderBy('name')->get(['id', 'name']);

    $query = \App\Models\User::select('users.*')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->where('roles.name', 'coach')
        ->with(['city', 'speciality'])
        ->withAvg('ratings', 'value')
        ->withCount('ratings');

    if ($request->filled('q')) {
        $term = '%' . trim((string) $request->q) . '%';
        $query->where(function ($builder) use ($term) {
            $builder->where('users.name', 'like', $term)
                ->orWhere('users.bio', 'like', $term)
                ->orWhereHas('city', fn ($q) => $q->where('name', 'like', $term))
                ->orWhereHas('speciality', fn ($q) => $q->where('name', 'like', $term));
        });
    }

    if ($request->filled('city')) {
        $query->whereIn('users.city_id', (array) $request->input('city'));
    }

    if ($request->filled('specialty')) {
        $query->whereIn('users.specialty_id', (array) $request->input('specialty'));
    }

    if ($request->filled('breed')) {
        $query->whereIn('users.breed_id', (array) $request->input('breed'));
    }

    $coaches = $query->paginate(9)->appends($request->query());

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
    $trainer = \App\Models\User::query()
        ->where('users.id', $id)
        ->whereHas('roles', fn ($q) => $q->where('name', 'coach'))
        ->with(['city', 'speciality', 'ratings'])
        ->withAvg('ratings', 'value')
        ->withCount('ratings')
        ->firstOrFail();

    $coursesQuery = \App\Models\Course::query()->where('user_id', $id);
    $coursesCount = (clone $coursesQuery)->count();
    $courses = $coursesQuery->latest()->take(8)->get();

    $specialties = $trainer->speciality ?? collect();
    $sessionResume = \App\Support\ProviderBookingResume::forProvider((int) $id);
    $profile = \App\Support\ProviderPublicProfile::summarize($trainer, $sessionResume['total']);
    $cityName = $profile['city_name'];

    $facts = array_values(array_filter([
        $cityName ? ['label' => 'شهر', 'value' => $cityName] : null,
        ['label' => 'امتیاز', 'value' => $profile['rating_count'] > 0 ? number_format($profile['rating_avg'], 1) : '—', 'num' => true],
        ['label' => 'دوره‌ها', 'value' => (string) $coursesCount, 'num' => true],
        ['label' => 'جلسات تکمیل‌شده', 'value' => (string) $sessionResume['total'], 'num' => true],
    ]));

    $bookUrl = route('frontend.booking.index', array_filter([
        'type' => 'coach',
        'provider' => (int) $trainer->id,
        'city_id' => $trainer->city_id ?: null,
    ]));

    return view('coaches.show', [
        'trainer' => $trainer,
        'courses' => $courses,
        'specialties' => $specialties,
        'cityName' => $cityName,
        'facts' => $facts,
        'bookUrl' => $bookUrl,
        'sessionResume' => $sessionResume,
        'profile' => $profile,
    ]);
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

public function listing(Request $request, $coachId = null)
{
    $coachId = $coachId ?: auth()->id();
    $coach = User::whereHas('roles', function ($q) {
        $q->where('name', 'coach');
    })->findOrFail($coachId);

    abort_unless((int) auth()->id() === (int) $coach->id || auth()->user()?->is_admin, 403);

    $courses = Course::query()
        ->where('user_id', $coach->id)
        ->latest()
        ->get();

    $products = Product::query()
        ->where('user_id', $coach->id)
        ->latest()
        ->paginate(15);

    $pendingRequests = Booking::query()
        ->where('provider_id', $coach->id)
        ->whereIn('status', ['PENDING', 'OPEN', 'QUOTED'])
        ->count();

    return view('coaches.listing', [
        'coach' => $coach,
        'courses' => $courses,
        'products' => $products,
        'pendingRequests' => $pendingRequests,
        'user' => $coach,
        'roleLabel' => 'مربی',
    ]);
}

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

    return redirect()->route('admin.coaches.index')->with('success', 'Coach created successfully');
}

    // Delete a coach
public function destroyList($id)
{
    $user = User::findOrFail($id);

    // Optional: check if user has coach role before deleting
    if (!$user->roles()->where('name', 'coach')->exists()) {
        return redirect()->route('admin.coaches.index')->with('error', 'This user is not a coach.');
    }

    $user->delete();

    return redirect()->route('admin.coaches.index')->with('success', 'Coach deleted successfully');
}


    public function coachWallet()
    {
        return redirect()->route('wallet.index');
    }

}



