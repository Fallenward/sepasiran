<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\Insurance;
use App\Models\InsuranceLicense;
use App\Models\InsurancePlan;
use App\Support\InsurancePurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ClinicInsuranceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Insurance::query()
            ->where('is_active', true)
            ->with(['activePlans' => fn ($q) => $q->orderBy('price')]);

        if ($request->filled('q')) {
            $term = '%'.trim((string) $request->q).'%';
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('contact_phone', 'like', $term)
                    ->orWhere('website', 'like', $term);
            });
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        $insurances = $query->latest()->paginate(9)->appends($request->query());

        $filters = [
            'featured' => [
                ['id' => '1', 'name' => 'ویژه'],
            ],
        ];

        return view('clinic-insurance.index', compact('insurances', 'filters'));
    }

    public function show(int $insurance): View
    {
        $insurance = Insurance::query()
            ->where('is_active', true)
            ->with(['activePlans'])
            ->findOrFail($insurance);

        $dogs = collect();
        $activeLicenses = collect();
        if (Auth::check()) {
            $dogs = Dog::query()
                ->where('user_id', Auth::id())
                ->orderByDesc('id')
                ->limit(30)
                ->get(['id', 'title']);
            $activeLicenses = InsuranceLicense::query()
                ->forUser((int) Auth::id())
                ->where('insurance_id', $insurance->id)
                ->active()
                ->with('plan')
                ->latest('ends_at')
                ->get();
        }

        return view('insurance.show', [
            'insurance' => $insurance,
            'plans' => $insurance->activePlans,
            'dogs' => $dogs,
            'activeLicenses' => $activeLicenses,
            'loginUrl' => route('login', ['redirect' => route('frontend.clinic.insurance.show', $insurance->id)]),
        ]);
    }

    public function purchase(Request $request, int $insurance, InsurancePurchaseService $purchase): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login', [
                'redirect' => route('frontend.clinic.insurance.show', $insurance),
            ]));
        }

        $insurer = Insurance::query()->where('is_active', true)->findOrFail($insurance);

        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:insurance_plans,id'],
            'dog_id' => ['nullable', 'integer', 'exists:dogs,id'],
            'payment_method' => ['nullable', 'in:wallet'],
        ], [
            'plan_id.required' => 'یک طرح بیمه را انتخاب کنید.',
        ]);

        $plan = InsurancePlan::query()
            ->whereKey($data['plan_id'])
            ->where('insurance_id', $insurer->id)
            ->where('is_active', true)
            ->firstOrFail();

        try {
            $license = $purchase->purchase(
                Auth::user(),
                $plan,
                isset($data['dog_id']) ? (int) $data['dog_id'] : null,
                $data['payment_method'] ?? 'wallet'
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()
            ->route('account.insurance-licenses.index')
            ->with('success', 'پرداخت انجام شد. مجوز '.$license->license_code.' تا '
                .optional($license->ends_at)->timezone(config('app.timezone', 'Asia/Tehran'))->format('Y/m/d')
                .' در داشبورد شما فعال است.');
    }

    public function myLicenses(): View
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $licenses = InsuranceLicense::query()
            ->forUser((int) $user->id)
            ->with(['insurance', 'plan', 'dog'])
            ->latest('id')
            ->paginate(12);

        $licenses->getCollection()->each(fn (InsuranceLicense $license) => $license->refreshStatus());

        return view('frontend.pages.account.insurance-licenses', [
            'licenses' => $licenses,
            'user' => $user,
            'roleLabel' => $user->primaryRoleLabel(),
        ]);
    }
}
