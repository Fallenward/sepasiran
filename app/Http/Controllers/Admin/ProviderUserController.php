<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProviderUserController extends Controller
{
    /**
     * @return array{role:string,route:string,label:string,label_plural:string,empty:string,param:string}
     */
    protected function meta(): array
    {
        if (request()->routeIs('admin.clinics.*')) {
            return [
                'role' => 'clinic',
                'route' => 'admin.clinics',
                'label' => 'کلینیک',
                'label_plural' => 'کلینیک‌ها',
                'empty' => 'کلینیکی ثبت نشده.',
                'param' => 'clinic',
            ];
        }

        if (request()->routeIs('admin.breeders.*')) {
            return [
                'role' => 'breeder',
                'route' => 'admin.breeders',
                'label' => 'پرورش‌دهنده',
                'label_plural' => 'پرورش‌دهنده‌ها',
                'empty' => 'پرورش‌دهنده‌ای ثبت نشده.',
                'param' => 'breeder',
            ];
        }

        abort(404);
    }

    protected function providerId(): int
    {
        $meta = $this->meta();

        return (int) request()->route($meta['param']);
    }

    protected function providerQuery(string $role)
    {
        return User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', $role))
            ->with(['city', 'roles']);
    }

    public function index(Request $request): View
    {
        $meta = $this->meta();

        $providers = $this->providerQuery($meta['role'])
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

        return view('admin.providers.index', compact('providers', 'meta'));
    }

    public function create(): View
    {
        $meta = $this->meta();
        $provider = new User();
        $cities = City::orderBy('name')->get();

        return view('admin.providers.create', compact('provider', 'cities', 'meta'));
    }

    public function store(Request $request): RedirectResponse
    {
        $meta = $this->meta();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6|confirmed',
            'city_id' => 'nullable|exists:cities,id',
            'bio' => 'nullable|string',
        ]);

        $provider = User::create([
            'name' => $validated['name'],
            'family' => $validated['family'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'city_id' => $validated['city_id'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'is_admin' => false,
        ]);

        $roleId = DB::table('roles')->where('name', $meta['role'])->value('id');
        if ($roleId) {
            $provider->roles()->syncWithoutDetaching([$roleId]);
        }

        return redirect()
            ->route($meta['route'] . '.index')
            ->with('success', $meta['label'] . ' با موفقیت ایجاد شد.');
    }

    public function show(): View
    {
        $meta = $this->meta();
        $provider = $this->providerQuery($meta['role'])->findOrFail($this->providerId());

        return view('admin.providers.show', compact('provider', 'meta'));
    }

    public function edit(): View
    {
        $meta = $this->meta();
        $provider = $this->providerQuery($meta['role'])->findOrFail($this->providerId());
        $cities = City::orderBy('name')->get();

        return view('admin.providers.edit', compact('provider', 'cities', 'meta'));
    }

    public function update(Request $request): RedirectResponse
    {
        $meta = $this->meta();
        $provider = $this->providerQuery($meta['role'])->findOrFail($this->providerId());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $provider->id,
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:6|confirmed',
            'city_id' => 'nullable|exists:cities,id',
            'bio' => 'nullable|string',
        ]);

        $provider->fill([
            'name' => $validated['name'],
            'family' => $validated['family'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        if (! empty($validated['password'])) {
            $provider->password = Hash::make($validated['password']);
        }

        $provider->save();

        return redirect()
            ->route($meta['route'] . '.index')
            ->with('success', $meta['label'] . ' به‌روزرسانی شد.');
    }

    public function destroy(): RedirectResponse
    {
        $meta = $this->meta();
        $provider = $this->providerQuery($meta['role'])->findOrFail($this->providerId());

        $roleId = DB::table('roles')->where('name', $meta['role'])->value('id');
        if ($roleId) {
            $provider->roles()->detach($roleId);
        }

        return redirect()
            ->route($meta['route'] . '.index')
            ->with('success', 'نقش ' . $meta['label'] . ' از کاربر برداشته شد.');
    }
}