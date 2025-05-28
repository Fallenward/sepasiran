@extends('frontend.layouts.header-dashboard')

@section('content')
<div class="container py-4">

    <!-- User Info and Menu -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/user-avatar.png') }}" alt="Avatar" class="rounded-circle shadow-sm" width="60">
            <div class="me-3">
                <h6 class="mb-1 fw-bold">حمید حیدری</h6>
                <small class="text-muted">کاربر | اعتبار: ۵۰,۰۰۰ تومان</small>
            </div>
        </div>
        <button class="btn btn-outline-secondary rounded-circle shadow-sm">
            <i class="bi bi-list fs-5"></i>
        </button>
    </div>

    <!-- Logo -->
    <div class="text-center mb-4">
        <img src="{{ asset('images/sepasiran-logo.png') }}" alt="Sepasiran" height="40">
    </div>

    <!-- Order Card -->
    <div class="card shadow border-0 mb-5">
        <div class="card-body py-4 px-4">

            <h4 class="card-title fw-bold text-primary mb-2">آموزش مقدماتی سگ</h4>
            <p class="text-muted mb-4 small">شماره سفارش: ۱۴۰۳/۰۲/۱۵ - ساعت ۱۰:۰۰</p>

            <!-- Step Progress -->
            <div class="d-flex justify-content-between text-center mb-4">
                <div>
                    <div class="step-circle border border-primary text-primary fw-bold">3</div>
                    <small class="d-block mt-2">انتخاب مربی</small>
                </div>
                <div>
                    <div class="step-circle border border-primary text-primary fw-bold">2</div>
                    <small class="d-block mt-2">دریافت پیشنهادات</small>
                </div>
                <div>
                    <div class="step-circle bg-primary text-white">
                        <i class="bi bi-check-lg fs-5"></i>1
                    </div>
                    <small class="d-block mt-2">ثبت سفارش</small>
                </div>
            </div>

            <!-- Suggestion Count -->
            <div class="d-flex justify-content-between align-items-center">
                <a href="#" class="text-decoration-none text-primary fw-semibold">مشاهده پیشنهادات</a>
                <span class="text-muted small">۳ / ۵ پیشنهاد دریافت شده</span>
            </div>

        </div>
    </div>

</div>

<!-- Bottom Navigation -->
<nav class="navbar fixed-bottom bg-white border-top shadow-sm py-2">
    <div class="container d-flex justify-content-around">
        <a class="text-center text-dark" href="#"><i class="bi bi-house-door fs-5"></i><br><small>داشبورد</small></a>
        <a class="text-center text-dark" href="#"><i class="bi bi-wallet2 fs-5"></i><br><small>تراکنش‌ها</small></a>
        <a class="text-center text-dark" href="#"><i class="bi bi-plus-circle fs-5"></i><br><small>ثبت سفارش</small></a>
        <a class="text-center text-dark" href="#"><i class="bi bi-person fs-5"></i><br><small>پروفایل</small></a>
    </div>
</nav>

<!-- Custom Styles -->
<style>
    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        line-height: 50px;
        font-size: 18px;
        display: inline-block;
        text-align: center;
    }
</style>
@endsection
