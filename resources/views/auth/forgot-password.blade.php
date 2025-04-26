@extends('layouts.auth')

@section('title', 'فراموشی رمز عبور')

@section('main')
    <div class="container d-flex justify-content-center align-items-center min-vh-100" dir="rtl">
        <div class="card shadow border-0 w-100" style="max-width: 640px; border-radius: 1rem;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">بازیابی رمز عبور</h4>
                    <p class="text-muted">لطفاً شماره موبایل خود را وارد کنید تا لینک بازنشانی رمز عبور برای شما ارسال شود.</p>
                </div>

                <form action="{{ route('password.phone') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="phone" class="form-label">شماره موبایل</label>
                        <div class="input-group" dir="rtl">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-mobile-alt text-muted"></i></span>
                            <input type="tel" name="phone" id="phone"
                                   class="form-control border-start-0 @error('phone') is-invalid @enderror"
                                   placeholder="مثلاً 09121234567" value="{{ old('phone') }}" dir="rtl">
                        </div>
                        @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">درخواست رمز عبور جدید</button>
                    </div>

                    <div class="text-center mt-2">
                        <a href="{{ route('login') }}" class="text-decoration-none">بازگشت به صفحه ورود</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
