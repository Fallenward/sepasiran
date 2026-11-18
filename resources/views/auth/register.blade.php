@extends('layouts.auth')

@section('title', 'ثبت‌نام')

@section('main')
@php
    $redirect = request('redirect', '');
    $signupRoles = $signupRoles ?? \App\Http\Controllers\Auth\RegisteredUserController::signupRoles();
    $selectedRole = old('role', 'user');
@endphp
<div class="auth-shell">
    <aside class="auth-brand" aria-hidden="true">
        <div>
            <p class="sp-brand__en latin" style="margin:0 0 8px">Sepasiran</p>
            <h1 class="sp-brand__fa" style="font-size:clamp(2.4rem,4vw,3.2rem);margin:0 0 14px">سپاسیران</h1>
            <p style="color:var(--sp-text-muted);max-width:22rem;margin:0;line-height:1.8">
                نقش خود را انتخاب کنید و حساب بسازید — مشتری، مربی، کلینیک یا پرورش‌دهنده.
            </p>
        </div>
    </aside>

    <div class="auth-form">
        <form class="panel auth-card auth-card--wide" action="{{ route('register.store') }}" method="POST" novalidate>
            @csrf
            @if($redirect)
                <input type="hidden" name="redirect" value="{{ $redirect }}">
            @endif

            <div class="auth-card__tabs" role="tablist">
                <a href="{{ route('login', $redirect ? ['redirect' => $redirect] : []) }}">ورود</a>
                <span class="is-active">ثبت‌نام</span>
            </div>

            <h1 class="auth-card__title">ساخت حساب</h1>
            <p class="auth-card__lede">نقش، نام، موبایل و رمز عبور را مشخص کنید.</p>

            @if ($errors->any())
                <div class="auth-alert" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <fieldset class="auth-roles">
                <legend>نقش شما</legend>
                <div class="auth-roles__grid">
                    @foreach($signupRoles as $key => $meta)
                        <label class="auth-role {{ $selectedRole === $key ? 'is-active' : '' }}">
                            <input type="radio" name="role" value="{{ $key }}" @checked($selectedRole === $key) required>
                            <span class="auth-role__label">{{ $meta['label'] }}</span>
                            <span class="auth-role__hint">{{ $meta['hint'] }}</span>
                        </label>
                    @endforeach
                </div>
                @error('role')<div class="auth-field-error">{{ $message }}</div>@enderror
            </fieldset>

            <div class="auth-grid">
                <div class="field">
                    <label for="name">نام</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="given-name" autofocus>
                    @error('name')<div class="auth-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="family">نام خانوادگی</label>
                    <input id="family" type="text" name="family" value="{{ old('family') }}" required autocomplete="family-name">
                    @error('family')<div class="auth-field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="phone">شماره موبایل</label>
                <input id="phone" class="num" type="tel" name="phone" value="{{ old('phone') }}"
                       placeholder="09xxxxxxxxx" required autocomplete="tel">
                @error('phone')<div class="auth-field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="email">ایمیل <span class="auth-optional">(اختیاری)</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       placeholder="name@example.com" autocomplete="email">
                @error('email')<div class="auth-field-error">{{ $message }}</div>@enderror
            </div>

            <div class="auth-grid">
                <div class="field">
                    <label for="password">رمز عبور</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')<div class="auth-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">تکرار رمز</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">ایجاد حساب</button>

            <p class="auth-card__foot">
                قبلاً ثبت‌نام کرده‌اید؟
                <a href="{{ route('login', $redirect ? ['redirect' => $redirect] : []) }}">ورود</a>
                ·
                <a href="{{ route('frontend.home') }}">خانه</a>
            </p>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.auth-role input').forEach(function (input) {
        input.addEventListener('change', function () {
            document.querySelectorAll('.auth-role').forEach(function (el) { el.classList.remove('is-active'); });
            input.closest('.auth-role').classList.add('is-active');
        });
    });
});
</script>
@endpush