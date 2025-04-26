@extends('layouts.admin')

@section('title', 'ثبت نام')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100" dir="rtl">
        <div class="card shadow border-0 w-100" style="max-width: 720px; border-radius: 1rem;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">ثبت نام</h3>
                    <p class="text-muted">لطفاً اطلاعات خود را برای ایجاد حساب وارد کنید</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">نام و نام خانوادگی</label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required autocomplete="name" autofocus dir="rtl">
                        @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

{{--                    <div class="mb-3">--}}
{{--                        <label for="email" class="form-label">ایمیل</label>--}}
{{--                        <input type="email" id="email" name="email"--}}
{{--                               class="form-control @error('email') is-invalid @enderror"--}}
{{--                               value="{{ old('email') }}" required autocomplete="email" dir="rtl">--}}
{{--                        @error('email')--}}
{{--                        <div class="invalid-feedback d-block">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

                    {{-- اگر می‌خواهی از شماره موبایل به‌جای ایمیل استفاده کنی این بخش رو جایگزین کن --}}

                    <div class="mb-3">
                        <label for="phone" class="form-label">شماره موبایل</label>
                        <input type="tel" id="phone" name="mobile"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}" required dir="rtl">
                        @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">رمز عبور</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required autocomplete="new-password" dir="rtl">
                        @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label">تایید رمز عبور</label>
                        <input type="password" id="password-confirm" name="password_confirmation"
                               class="form-control" required autocomplete="new-password" dir="rtl">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">ثبت نام</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
