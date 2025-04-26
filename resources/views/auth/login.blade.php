@extends('layouts.auth')

@section('title', 'ورود به حساب کاربری')

@section('main')
    <div class="container d-flex justify-content-center align-items-center min-vh-100" dir="rtl">
        <div class="card shadow border-0 w-100" style="max-width: 640px; border-radius: 1rem;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <a href="/" class="d-block mb-2 text-decoration-none">
                        <h2 class="fw-bold text-primary">ApexOnline</h2>
                    </a>
                    <h4 class="fw-semibold">ورود به حساب</h4>
                    <p class="text-muted">برای شروع جلسه، اطلاعات حساب خود را وارد کنید</p>
                </div>

                <form action="{{ route('new-login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">ایمیل</label>
                        <div class="input-group" dir="rtl">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-mobile-alt text-muted"></i></span>
                            <input type="email" id="email" name="email"
                                   class="form-control border-start-0 text-end"
                                   placeholder="ایمیل" dir="rtl">
                        </div>
                    </div>
                    @error('email')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror


                    <div class="mb-4">
                        <label for="password" class="form-label">رمز عبور</label>
                        <div class="input-group" dir="rtl">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" id="password" name="password"
                                   class="form-control border-start-0 text-end"
                                   placeholder="رمز عبور خود را وارد کنید" dir="rtl">
                        </div>
                    </div>
                    @error('password')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror


                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">ورود به حساب</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
