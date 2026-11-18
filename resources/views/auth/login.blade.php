@extends('layouts.auth')

@section('title', 'ورود به حساب کاربری')

@section('main')
<div class="auth-shell">
    <aside class="auth-brand">
        <div>
            <p class="sp-brand__en latin" style="margin:0 0 8px">Sepasiran</p>
            <h1 class="sp-brand__fa" style="font-size:3rem;margin:0 0 12px">سپاسیران</h1>
            <p style="color:var(--sp-text-muted);max-width:20rem;margin:0">ورود امن با حس برند — نه اسکین AdminLTE.</p>
        </div>
    </aside>

    <div class="auth-form">
        <form class="panel" style="width:min(100%,400px)" action="{{ route('new-login') }}" method="POST">
            @csrf
            <h1 style="margin:0 0 8px;font-size:1.5rem">ورود</h1>
            <p style="margin:0 0 20px;color:var(--sp-text-subtle);font-size:0.9rem">ایمیل و رمز عبور حساب خود را وارد کنید</p>

            <div class="field">
                <label for="email">ایمیل</label>
                <input id="email" class="num" type="email" name="email" value="{{ old('email') }}"
                       placeholder="name@example.com" autocomplete="username" required>
                @error('email')
                    <div style="color:var(--sp-danger);font-size:0.8rem;margin-top:6px">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="password">رمز عبور</label>
                <input id="password" type="password" name="password" autocomplete="current-password" required>
                @error('password')
                    <div style="color:var(--sp-danger);font-size:0.8rem;margin-top:6px">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block">ورود به حساب</button>
            <p style="margin:16px 0 0;text-align:center;font-size:0.85rem;color:var(--sp-text-subtle)">
                <a href="{{ route('frontend.home') }}">بازگشت به خانه</a>
            </p>
        </form>
    </div>
</div>
@endsection
