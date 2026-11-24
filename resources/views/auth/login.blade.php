@extends('layouts.auth')

@section('title', 'ورود')

@section('main')
@php $redirect = $redirect ?? request('redirect', ''); @endphp
<div class="auth-shell">
    <aside class="auth-brand" aria-hidden="true">
        <div>
            <p class="sp-brand__en latin" style="margin:0 0 8px">Sepasiran</p>
            <h1 class="sp-brand__fa" style="font-size:clamp(2.4rem,4vw,3.2rem);margin:0 0 14px">سپاسیران</h1>
            <p style="color:var(--sp-text-muted);max-width:22rem;margin:0;line-height:1.8">
                ورود به حساب برای رزرو خدمت، مدیریت سگ‌ها و پیگیری درخواست‌ها.
            </p>
        </div>
    </aside>

    <div class="auth-form">
        <form class="panel auth-card" action="{{ route('login.store') }}" method="POST" novalidate>
            @csrf
            @if($redirect)
                <input type="hidden" name="redirect" value="{{ $redirect }}">
            @endif

            <div class="auth-card__tabs" role="tablist">
                <span class="is-active">ورود</span>
                <a href="{{ route('register', $redirect ? ['redirect' => $redirect] : []) }}">ثبت‌نام</a>
            </div>

            <h1 class="auth-card__title">خوش آمدید</h1>
            <p class="auth-card__lede">ایمیل یا شماره موبایل و رمز عبور را وارد کنید.</p>

            @if ($errors->any())
                <div class="auth-alert" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="field">
                <label for="login">ایمیل یا موبایل</label>
                <input id="login" class="num" type="text" name="login" value="{{ old('login', old('email')) }}"
                       placeholder="09xxxxxxxxx یا name@email.com" autocomplete="username" required autofocus>
            </div>

            <div class="field">
                <label for="password">رمز عبور</label>
                <input id="password" type="password" name="password" autocomplete="current-password" required>
            </div>

            <div class="auth-card__row">
                <label class="auth-check">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    <span>مرا به خاطر بسپار</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}">فراموشی رمز</a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary btn-block">ورود به حساب</button>

            <p class="auth-card__foot">
                حساب ندارید؟
                <a href="{{ route('register', $redirect ? ['redirect' => $redirect] : []) }}">ثبت‌نام کنید</a>
                ·
                <a href="{{ route('frontend.home') }}">خانه</a>
            </p>
        </form>
    </div>
</div>
@endsection
