<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\ProviderServicePrice;
use App\Support\BookingCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProviderServicePriceController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();
        abort_unless($user, 401);

        $role = $this->providerRole($user);
        abort_unless($role, 403);

        $services = BookingCatalog::priceableServicesForRole($role);
        $existing = ProviderServicePrice::query()
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('service_type');

        $rows = [];
        foreach ($services as $service) {
            $row = $existing->get($service['key']);
            $rows[] = [
                'key' => $service['key'],
                'label' => $service['label'],
                'default_price' => (int) $service['default_price'],
                'base_price' => $row ? (int) $row->base_price : (int) $service['default_price'],
                'training_surcharges' => $row?->training_surcharges ?? [],
            ];
        }

        return view('frontend.pages.account.service-prices', [
            'role' => $role,
            'rows' => $rows,
            'trainingTypes' => $role === 'coach' ? BookingCatalog::trainingTypes() : [],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user, 401);

        $role = $this->providerRole($user);
        abort_unless($role, 403);

        $allowed = collect(BookingCatalog::priceableServicesForRole($role))->pluck('key')->all();
        $payload = $request->validate([
            'prices' => ['required', 'array'],
            'prices.*.base_price' => ['required', 'integer', 'min:0', 'max:999999999'],
            'prices.*.training_surcharges' => ['nullable', 'array'],
            'prices.*.training_surcharges.*' => ['nullable', 'integer', 'min:0', 'max:999999999'],
        ]);

        foreach ($payload['prices'] as $serviceType => $data) {
            if (! in_array($serviceType, $allowed, true)) {
                continue;
            }

            $training = [];
            if ($serviceType === 'coach' && $role === 'coach') {
                foreach (BookingCatalog::trainingKeys() as $key) {
                    if (isset($data['training_surcharges'][$key]) && $data['training_surcharges'][$key] !== null && $data['training_surcharges'][$key] !== '') {
                        $training[$key] = (int) $data['training_surcharges'][$key];
                    }
                }
            }

            ProviderServicePrice::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'service_type' => $serviceType,
                ],
                [
                    'base_price' => (int) $data['base_price'],
                    'training_surcharges' => $training !== [] ? $training : null,
                ]
            );
        }

        return back()->with('success', 'قیمت خدمات ذخیره شد.');
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
