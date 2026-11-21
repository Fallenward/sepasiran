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
    $cities = City::orderBy('name')->get(['id', 'name']);
    $specialties = Specialty::orderBy('name')->get(['id', 'name']);

    $query = \App\Models\User::select('users.*')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->where('roles.name', 'clinic')
        ->with(['city', 'speciality']);

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

    $clinics = $query->paginate(9)->appends($request->query());

    $filters = [
        'city' => $cities,
        'specialty' => $specialties,
    ];

    return view('clinics.index', compact('clinics', 'filters'));
}

    public function clinicwallet()
    {
        return redirect()->route('wallet.index');
    }

        public function dashboard()
    {
        $clinics = User::all();

        return view('admin.clinics.dashboard', compact('clinics'));
    }



    public function showClinicById($id)
{
    $clinic = \App\Models\User::query()
        ->where('users.id', $id)
        ->whereHas('roles', fn ($q) => $q->where('name', 'clinic'))
        ->with(['speciality', 'roles'])
        ->firstOrFail();

    $cityName = null;
    if (! empty($clinic->city_id)) {
        $cityName = optional(\App\Models\City::query()->find($clinic->city_id))->name;
    } elseif (is_string($clinic->getAttribute('city')) && $clinic->getAttribute('city') !== '') {
        $cityName = $clinic->getAttribute('city');
    }

    $specialties = $clinic->speciality ?? collect();
    $sessionResume = \App\Support\ProviderBookingResume::forProvider((int) $id);

    $workingHours = $clinic->getAttribute('working_hours');
    $animals = $clinic->getAttribute('animals');
    $address = trim((string) ($clinic->address ?? ''));
    $address = $address !== '' ? $address : null;

    $facts = array_values(array_filter([
        $cityName ? ['label' => 'شهر', 'value' => $cityName] : null,
        $address ? ['label' => 'آدرس', 'value' => \Illuminate\Support\Str::limit($address, 40)] : null,
        $workingHours ? ['label' => 'ساعت کاری', 'value' => (string) $workingHours] : null,
        ['label' => 'نوبت‌های تکمیل‌شده', 'value' => (string) $sessionResume['total'], 'num' => true],
        ['label' => 'تخصص‌ها', 'value' => (string) $specialties->count(), 'num' => true],
    ]));

    $bookUrl = route('frontend.booking.index', array_filter([
        'type' => 'clinic',
        'provider' => (int) $clinic->id,
        'city_id' => $clinic->city_id ?: null,
    ]));

    return view('clinics.show', [
        'trainer' => $clinic,
        'specialties' => $specialties,
        'cityName' => $cityName,
        'facts' => $facts,
        'bookUrl' => $bookUrl,
        'workingHours' => $workingHours,
        'animals' => $animals,
        'address' => $address,
        'sessionResume' => $sessionResume,
    ]);
}
 public function edit()
    {
        $user = Auth::user();

        return view('admin.clinics.profile', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

           $data = $request->validate([
        'clinic_name' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'working_hours' => 'nullable|string|max:255',
        'animals' => 'nullable|array',
        'social' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'province' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'full_address' => 'nullable|string|max:255',
    ]);

    $user->name = $data['clinic_name'];
    $user->phone = $data['phone'];
    $user->working_hours = $data['working_hours'] ?? null;
    $user->animals = isset($data['animals']) ? implode(',', $data['animals']) : null;
    $user->social = $data['social'] ?? null;
    $user->bio = $data['description'];
    $user->province = $data['province'] ?? null;
    $user->city = $data['city'] ?? null;
    $user->address = $data['full_address'];




        $user->save();

        return redirect()->route('profile.edit')->with('success', 'اطلاعات با موفقیت به‌روزرسانی شد.');
    }

}
