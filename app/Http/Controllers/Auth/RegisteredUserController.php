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
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'family' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'نام الزامی است.',
            'family.required' => 'نام خانوادگی الزامی است.',
            'phone.required' => 'شماره موبایل الزامی است.',
            'phone.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',
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

        $role = Role::query()->firstOrCreate(['name' => 'user']);
        if (method_exists($user, 'roles')) {
            $user->roles()->syncWithoutDetaching([$role->id]);
        }

        event(new Registered($user));
        Auth::login($user);

        $redirect = $request->query('redirect') ?: $request->input('redirect');
        if (is_string($redirect) && str_starts_with($redirect, '/') && !str_starts_with($redirect, '//')) {
            return redirect()->to($redirect);
        }

        return redirect()->intended(route('frontend.home'));
    }
}