<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\ProviderAvailabilityException;
use App\Models\ProviderAvailabilityRule;
use App\Models\ProviderAvailabilitySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProviderAvailabilityController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();
        abort_unless($user && $this->isProvider($user), 403);

        $settings = ProviderAvailabilitySetting::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['min_notice_minutes' => 120, 'max_advance_days' => 60, 'slot_interval_minutes' => 60]
        );

        $rules = ProviderAvailabilityRule::query()
            ->where('user_id', $user->id)
            ->orderBy('weekday')
            ->orderBy('start_time')
            ->get();

        $exceptions = ProviderAvailabilityException::query()
            ->where('user_id', $user->id)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->get();

        return view('frontend.pages.account.provider-availability', [
            'settings' => $settings,
            'rules' => $rules,
            'exceptions' => $exceptions,
            'weekdays' => [
                0 => 'یکشنبه', 1 => 'دوشنبه', 2 => 'سه‌شنبه', 3 => 'چهارشنبه',
                4 => 'پنجشنبه', 5 => 'جمعه', 6 => 'شنبه',
            ],
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && $this->isProvider($user), 403);

        $data = $request->validate([
            'min_notice_minutes' => ['required', 'integer', 'min:0', 'max:10080'],
            'max_advance_days' => ['required', 'integer', 'min:1', 'max:365'],
            'slot_interval_minutes' => ['nullable', 'integer', 'min:15', 'max:240'],
        ]);

        ProviderAvailabilitySetting::query()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return back()->with('success', 'تنظیمات دسترسی ذخیره شد.');
    }

    public function storeRule(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && $this->isProvider($user), 403);

        $data = $request->validate([
            'weekday' => ['required', 'integer', 'min:0', 'max:6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'is_break' => ['nullable', 'boolean'],
        ]);

        ProviderAvailabilityRule::query()->create([
            'user_id' => $user->id,
            'weekday' => (int) $data['weekday'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'is_break' => $request->boolean('is_break'),
        ]);

        return back()->with('success', 'بازه زمانی اضافه شد.');
    }

    public function destroyRule(ProviderAvailabilityRule $rule): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && (int) $rule->user_id === (int) $user->id, 403);
        $rule->delete();

        return back()->with('success', 'بازه حذف شد.');
    }

    public function storeException(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && $this->isProvider($user), 403);

        $data = $request->validate([
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'all_day' => ['nullable', 'boolean'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'kind' => ['required', 'in:unavailable,block'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        ProviderAvailabilityException::query()->create([
            'user_id' => $user->id,
            'date' => $data['date'],
            'all_day' => $request->boolean('all_day', true),
            'start_time' => $data['start_time'] ?? null,
            'end_time' => $data['end_time'] ?? null,
            'kind' => $data['kind'],
            'note' => $data['note'] ?? null,
        ]);

        return back()->with('success', 'مسدودسازی تاریخ ذخیره شد.');
    }

    public function destroyException(ProviderAvailabilityException $exception): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && (int) $exception->user_id === (int) $user->id, 403);
        $exception->delete();

        return back()->with('success', 'مسدودسازی حذف شد.');
    }

    private function isProvider($user): bool
    {
        foreach (['coach', 'clinic', 'breeder'] as $role) {
            if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                return true;
            }
        }

        return false;
    }
}