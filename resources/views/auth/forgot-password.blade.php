@extends('layouts.auth')

@section('title', 'فراموشی رمز عبور')

@section('main')
<div class="auth-shell">
    <aside class="auth-brand" aria-hidden="true">
        <div>
            <p class="sp-brand__en latin" style="margin:0 0 8px">Sepasiran</p>
            <h1 class="sp-brand__fa" style="font-size:clamp(2.4rem,4vw,3.2rem);margin:0 0 14px">سپاسیران</h1>
            <p style="color:var(--sp-text-muted);max-width:22rem;margin:0;line-height:1.8">
                لینک بازیابی رمز به ایمیل حساب شما ارسال می‌شود.
            </p>
        </div>
    </aside>

    <div class="auth-form">
        <form class="panel auth-card" action="{{ route('password.email') }}" method="POST">
            @csrf
            <h1 class="auth-card__title">بازیابی رمز</h1>
            <p class="auth-card__lede">ایمیل حساب خود را وارد کنید.</p>

            @if (session('status'))
                <div class="auth-alert auth-alert--ok" role="status">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="auth-alert" role="alert">{{ $errors->first() }}</div>
            @endif

            <div class="field">
                <label for="email">ایمیل</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            </div>

            <button type="submit" class="btn btn-primary btn-block">ارسال لینک بازیابی</button>
            <p class="auth-card__foot">
                <a href="{{ route('login') }}">بازگشت به ورود</a>
            </p>
        </form>
    </div>
</div>
@endsection
