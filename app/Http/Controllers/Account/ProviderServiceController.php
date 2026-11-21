<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\ProviderService;
use App\Models\Specialty;
use App\Support\BookingCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProviderServiceController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        abort_unless($user && $this->providerRole($user), 403);

        $services = ProviderService::query()
            ->where('user_id', $user->id)
            ->with('specialty:id,name')
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        return view('frontend.pages.account.provider-services', [
            'services' => $services,
            'catalog' => BookingCatalog::priceableServicesForRole($this->providerRole($user)),
            'specialties' => Specialty::query()->where('user_id', $user->id)->orderBy('name')->get(),
            'trainingTypes' => BookingCatalog::trainingTypes(),
            'role' => $this->providerRole($user),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $role = $this->providerRole($user);
        abort_unless($user && $role, 403);

        $allowed = collect(BookingCatalog::priceableServicesForRole($role))->pluck('key')->all();
        $data = $request->validate([
            'service_type' => ['required', 'in:'.implode(',', $allowed)],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'specialty_id' => ['nullable', 'integer', 'exists:specialties,id'],
            'base_price' => ['required', 'integer', 'min:0', 'max:999999999'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:480'],
            'is_active' => ['nullable', 'boolean'],
            'training_surcharges' => ['nullable', 'array'],
            'training_surcharges.*' => ['nullable', 'integer', 'min:0'],
        ]);

        if (! empty($data['specialty_id'])) {
            $owns = Specialty::query()->whereKey($data['specialty_id'])->where('user_id', $user->id)->exists();
            abort_unless($owns, 403);
        }

        $training = [];
        if ($data['service_type'] === 'coach') {
            foreach (BookingCatalog::trainingKeys() as $key) {
                if (isset($data['training_surcharges'][$key]) && $data['training_surcharges'][$key] !== '') {
                    $training[$key] = (int) $data['training_surcharges'][$key];
                }
            }
        }

        ProviderService::query()->create([
            'user_id' => $user->id,
            'service_type' => $data['service_type'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'specialty_id' => $data['specialty_id'] ?? null,
            'base_price' => (int) $data['base_price'],
            'duration_minutes' => (int) $data['duration_minutes'],
            'training_surcharges' => $training !== [] ? $training : null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'خدمت ذخیره شد.');
    }

    public function update(Request $request, ProviderService $service): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && (int) $service->user_id === (int) $user->id, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'specialty_id' => ['nullable', 'integer', 'exists:specialties,id'],
            'base_price' => ['required', 'integer', 'min:0', 'max:999999999'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:480'],
            'is_active' => ['nullable', 'boolean'],
            'training_surcharges' => ['nullable', 'array'],
            'training_surcharges.*' => ['nullable', 'integer', 'min:0'],
        ]);

        $training = $service->training_surcharges;
        if ($service->service_type === 'coach' && isset($data['training_surcharges'])) {
            $training = [];
            foreach (BookingCatalog::trainingKeys() as $key) {
                if (isset($data['training_surcharges'][$key]) && $data['training_surcharges'][$key] !== '') {
                    $training[$key] = (int) $data['training_surcharges'][$key];
                }
            }
        }

        $service->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'specialty_id' => $data['specialty_id'] ?? null,
            'base_price' => (int) $data['base_price'],
            'duration_minutes' => (int) $data['duration_minutes'],
            'training_surcharges' => $training,
            'is_active' => $request->boolean('is_active', $service->is_active),
        ]);

        return back()->with('success', 'خدمت به‌روزرسانی شد.');
    }

    public function destroy(ProviderService $service): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && (int) $service->user_id === (int) $user->id, 403);
        $service->delete();

        return back()->with('success', 'خدمت حذف شد.');
    }

    private function providerRole($user): ?string
    {
        foreach (['coach', 'clinic', 'breeder'] as $role) {
            if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                return $role;
            }
        }

        return null;
    }
}