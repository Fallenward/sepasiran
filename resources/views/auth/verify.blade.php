@extends('layouts.admin')

@section('title', 'تأیید آدرس ایمیل')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100" dir="rtl">
        <div class="card shadow border-0 w-100" style="max-width: 720px; border-radius: 1rem;">
            <div class="card-body p-5">
                <h4 class="fw-bold mb-4 text-center">تأیید آدرس ایمیل</h4>

                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        یک لینک تأیید جدید به آدرس ایمیل شما ارسال شد.
                    </div>
                @endif

                <p class="mb-3">
                    قبل از ادامه، لطفاً ایمیل خود را بررسی کنید و لینک تأیید ارسال‌شده را باز کنید.
                </p>

                <p class="mb-4">
                    اگر ایمیلی دریافت نکرده‌اید، می‌توانید درخواست ارسال مجدد لینک را ثبت کنید:
                </p>

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">ارسال مجدد لینک تأیید</button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('logout') }}" class="text-muted text-decoration-none"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        خروج از حساب کاربری
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
