<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /** Roles guests may self-select at signup (never admin). */
    public static function signupRoles(): array
    {
        return [
            'user' => [
                'label' => 'صاحب سگ / مشتری',
                'hint' => 'رزرو خدمت و مدیریت سگ‌های خود',
            ],
            'coach' => [
                'label' => 'مربی',
                'hint' => 'دریافت درخواست آموزش و رزرو',
            ],
            'clinic' => [
                'label' => 'کلینیک',
                'hint' => 'نوبت‌دهی و خدمات درمانی',
            ],
            'breeder' => [
                'label' => 'پرورش‌دهنده',
                'hint' => 'ثبت و عرضه سگ',
            ],
        ];
    }

    public function create(): View
    {
        return view('auth.register', [
            'signupRoles' => self::signupRoles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $roleKeys = array_keys(self::signupRoles());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'family' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', Rule::in($roleKeys)],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'نام الزامی است.',
            'family.required' => 'نام خانوادگی الزامی است.',
            'phone.required' => 'شماره موبایل الزامی است.',
            'phone.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',
            'role.required' => 'نقش خود را انتخاب کنید.',
            'role.in' => 'نقش انتخاب‌شده معتبر نیست.',
            'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد.',
        ]);

        $email = $validated['email'] ?? null;
        if (!$email) {
            $digits = preg_replace('/\D+/', '', $validated['phone']);
            $email = $digits.'@phone.sepasiran.local';
            if (User::query()->where('email', $email)->exists()) {
                $email = $digits.'.'.uniqid().'@phone.sepasiran.local';
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'family' => $validated['family'],
            'phone' => $validated['phone'],
            'email' => $email,
            'password' => Hash::make($validated['password']),
            'status' => 'active',
            'is_admin' => false,
            'email_verified_at' => now(),
            'is_verified' => true,
        ]);

        $selected = Role::query()->firstOrCreate(['name' => $validated['role']]);
        $roleIds = [$selected->id];

        // Keep marketplace customer access for providers too (booking / owned dogs).
        if ($validated['role'] !== 'user') {
            $customer = Role::query()->firstOrCreate(['name' => 'user']);
            $roleIds[] = $customer->id;
        }

        $user->roles()->sync(array_values(array_unique($roleIds)));

        event(new Registered($user));
        Auth::login($user);

        $redirect = $request->query('redirect') ?: $request->input('redirect');
        if (is_string($redirect) && str_starts_with($redirect, '/') && !str_starts_with($redirect, '//')) {
            return redirect()->to($redirect);
        }

        return redirect()->to($this->homeForRole($validated['role']));
    }

    private function homeForRole(string $role): string
    {
        return match ($role) {
            'breeder' => route('breeder.dashboard'),
            'coach' => route('coaches.dashboard'),
            'clinic' => route('clinic.dashboard'),
            default => route('user.dashboard'),
        };
    }
}