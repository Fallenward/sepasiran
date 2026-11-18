<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();

        return view('frontend.pages.account.profile', [
            'user' => $user,
            'roleLabel' => $user->primaryRoleLabel(),
            'backUrl' => $user->dashboardRoute(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'family' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,' . $user->id],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ], [
            'name.required' => 'نام الزامی است.',
            'family.required' => 'نام خانوادگی الزامی است.',
            'phone.required' => 'شماره موبایل الزامی است.',
            'phone.unique' => 'این شماره قبلاً ثبت شده است.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',
            'profile_picture.image' => 'فایل تصویر معتبر نیست.',
            'profile_picture.max' => 'حجم تصویر باید کمتر از ۲ مگابایت باشد.',
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('avatars', 'public');
            if (! empty($user->profile_picture) && str_starts_with((string) $user->profile_picture, '/storage/avatars/')) {
                $old = str_replace('/storage/', '', $user->profile_picture);
                Storage::disk('public')->delete($old);
            }
            $validated['profile_picture'] = '/storage/' . $path;
        } else {
            unset($validated['profile_picture']);
        }

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if (empty($validated['email'])) {
            unset($validated['email']);
        }

        $user->fill($validated);
        $user->save();

        return redirect()
            ->route('account.profile.edit')
            ->with('success', 'پروفایل با موفقیت به‌روزرسانی شد.');
    }
}
