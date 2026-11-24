<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
            'redirect' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'ایمیل یا شماره موبایل الزامی است.',
            'password.required' => 'رمز عبور الزامی است.',
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = trim((string) $this->input('login'));
        $user = $this->findUser($login);

        if (!$user || !Hash::check((string) $this->input('password'), (string) $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => 'اطلاعات ورود صحیح نیست.',
            ]);
        }

        Auth::login($user, $this->boolean('remember'));
        RateLimiter::clear($this->throttleKey());
    }

    private function findUser(string $login): ?User
    {
        if (str_contains($login, '@')) {
            return User::query()->where('email', $login)->first();
        }

        $digits = preg_replace('/\D+/', '', $login);

        return User::query()
            ->where(function ($q) use ($login, $digits) {
                $q->where('phone', $login)
                    ->orWhere('email', $login);
                if ($digits !== '') {
                    $q->orWhere('phone', $digits)
                        ->orWhere('phone', 'like', '%'.$digits);
                }
            })
            ->first();
    }

    public function ensureIsNotRateLimited(): void
    {
        $maxAttempts = (int) config('rate_limiting.login.max_attempts', 5);

        if (!RateLimiter::tooManyAttempts($this->throttleKey(), $maxAttempts)) {
            return;
        }

        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => "تعداد دفعات ورود بیش از حد مجاز است. لطفاً تا {$seconds} ثانیه دیگر صبر کنید.",
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
