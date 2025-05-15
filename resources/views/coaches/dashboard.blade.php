@extends('admin.layout')

@section('title', 'داشبورد - Sepasiran')

@section('header')
    <header class="custom-header d-flex align-items-center justify-content-center">
        <h5 class="text-white m-0">داشبورد Sepasiran</h5>
    </header>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-lg-8">
                @foreach ($users as $user)
                    <h4 class="mb-3">سلام، {{ $user->name }}</h4>
                @endforeach
                <div class="d-flex align-items-center flex-wrap">
                    <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" width="50" class="rounded-circle me-2 mb-2">
                    <div>
                        <div>{{ $user->name }}</div>
                        <small>{{ $user->role }} | اعتبار: {{ $user->credit ?? 0 }} تومان</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 text-lg-end text-center mt-3 mt-lg-0">
                <strong>Sepasiran</strong>
            </div>
        </div>

        <!-- Search -->
        <div class="input-group my-4">
            <input type="text" class="form-control" placeholder="جست‌وجو..." aria-label="Search">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
        </div>

        <!-- Notification -->
        <div class="card mb-4" style="border: 3px solid #0D47A1;">
            <div class="card-header text-white" style="background-color: #0d47a1;">
                <strong>اطلاعیه</strong>
            </div>
            <div class="card-body bg-white text-dark text-end">
                لورم ایپسوم متن ساختگی جهت نمایش.
                <br>
                <small><i class="bi bi-calendar"></i> ۲۴ دی ۱۴۰۲</small>
            </div>
        </div>

        <!-- Shortcuts Section -->

        <div class="row text-center mb-4">
            @php
                $shortcuts = [
                    ['icon' => 'bi-cash', 'label' => 'تگ‌های آماده فروش'],
                    ['icon' => 'bi-people', 'label' => 'تعداد کل حیوانات'],
                    ['icon' => 'bi-clock-history', 'label' => 'در انتظار خروج'],
                    ['icon' => 'bi-clock-history', 'label' => 'در انتظار خروج'],
                ];
            @endphp

            @foreach ($shortcuts as $shortcut)
                <div class="col-6 col-sm-4 col-md-3 mb-3">
                    <div class="shortcut-icon">
                        <i class="bi {{ $shortcut['icon'] }} fs-4"></i>
                    </div>
                    <div class="shortcut-label mt-2">{{ $shortcut['label'] }}</div>
                </div>
            @endforeach
        </div>

        <!-- Title -->
        <h5 class="text-start mb-3">دوره های من</h5>

        <!-- Courses Section -->
        <div class="card shadow-sm mb-5" style="background-color: #0d47a1; padding: 20px; border-radius: 12px;">
            <!-- Repeatable Course Card -->
            <div class="row g-0 align-items-center mb-3 bg-white" style="border-radius: 12px; overflow: hidden;">
                <div class="col-12 col-md-9 p-3 text-end">
                    <span class="badge bg-primary mb-2">فعال</span>
                    <h5 class="card-title">دوره حفاظت و نگهبانی</h5>
                    <p class="card-text text-muted small">
                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است
                    </p>
                    <div class="row text-muted small">
                        <div class="col-6 col-sm-6 col-md-3">قیمت دوره: 100,000 تومان</div>
                        <div class="col-6 col-sm-6 col-md-3">تعداد شرکت‌کنندگان: 35 نفر</div>
                        <div class="col-6 col-sm-6 col-md-3">تاریخ شروع و پایان: 3 شهریور 1403 تا 15 آبان 1403</div>
                        <div class="col-6 col-sm-6 col-md-3">وضعیت دوره: در حال برگزاری</div>
                    </div>
                </div>
                <div class="col-12 col-md-3 text-center p-3">
                    <img src="your-image.jpg" class="img-fluid rounded-end" alt="course image" style="max-height: 100px;">
                </div>
            </div>
            <!-- /Course Card -->

            <div class="text-center mt-3">
                <a href="#" class="text-white text-decoration-none">مشاهده بیشتر ^</a>
            </div>
        </div>

        <!-- Footer Nav -->
        <nav class="footer-nav">
            <a class="nav-link" href="#"><i class="bi bi-house"></i><br>خانه</a>
            <a class="nav-link" href="#"><i class="bi bi-receipt"></i><br>سفارشات</a>
            <a class="nav-link" href="{{ route('breeders.dashboard') }}"><i class="bi bi-plus-circle"></i><br>افزودن</a>
            <a class="nav-link" href="#"><i class="bi bi-gear"></i><br>مدیریت</a>
            <a class="nav-link" href="#"><i class="bi bi-person"></i><br>پروفایل</a>
        </nav>
    </div>
@endsection

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


<style>
    body {
        padding-bottom: 100px;
        background-color: #f8f9fa;
        font-family: Tahoma, sans-serif;
    }

    .custom-header {
        position: absolute;
        width: 100%;
        height: 58px;
        left: 0;
        top: 0;
        background-color: #0d47a1;
        color: white;
        z-index: 1000;
        padding: 0 1rem;
    }

    .shortcut-icon {
        background-color: #0D47A1;
        color: #fff;
        padding: 20px;
        border-radius: 50%;
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        transition: transform 0.2s;
    }

    .shortcut-icon:hover {
        transform: scale(1.1);
    }

    .footer-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #f8f9fa;
        border-top: 1px solid #ccc;
        display: flex;
        justify-content: space-around;
        padding: 0.5rem 0;
        z-index: 1001;
    }

    .footer-nav .nav-link {
        text-align: center;
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .custom-header h5 {
            font-size: 1rem;
        }

        .card-title {
            font-size: 1rem;
        }

        .card-text, .card-body .row .col-md-3 {
            font-size: 0.75rem;
        }

        .shortcut-icon {
            width: 60px;
            height: 60px;
        }

        .footer-nav .nav-link {
            font-size: 0.75rem;
        }
    }
</style>

