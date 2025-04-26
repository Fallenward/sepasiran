@extends('layouts.auth')

@section('title', 'فراموشی رمز عبور')

@section('main')
    <div class="container d-flex justify-content-center align-items-center min-vh-100" dir="rtl">
        <div class="card shadow border-0 w-100" style="max-width: 640px; border-radius: 1rem;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">تنظیم رمز عبور جدید</h4>
                    <p class="text-muted">رمز عبور جدید خود را وارد و تأیید کنید</p>
                </div>

                <form action="{{ route('password.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="{{ $request->email }}">
                    <input type="hidden" name="token" value="{{ $request->token }}">

                    <div class="mb-3">
                        <label for="password" class="form-label">رمز عبور جدید</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" name="password" id="password"
                                   class="form-control border-start-0 @error('password') is-invalid @enderror"
                                   placeholder="رمز عبور جدید را وارد کنید" dir="rtl">
                        </div>
                        @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">تأیید رمز عبور</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control border-start-0"
                                   placeholder="تأیید رمز عبور را وارد کنید" dir="rtl">
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">ذخیره رمز عبور جدید</button>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none">بازگشت به صفحه ورود</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
