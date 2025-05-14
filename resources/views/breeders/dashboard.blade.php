@extends('admin.layout')

@section('title', 'داشبورد - Sepasiran')

@section('header')
<header class="custom-header d-flex align-items-center justify-content-center">
    <h5 class="text-white m-0">داشبورد Sepasiran</h5>
</header>
@endsection

@section('content')

    <style>
        body {
            padding-bottom: 80px;
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
            border-bottom: 1px solid #ccc;
            padding: 0 1rem;
        }

        .shortcut-icon {
            background-color: #0D47A1;
            color: #fff;
            padding: 20px;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
        }

        .square-card {
            background-color: #0d47a1;
            color: #fff;
            border-radius: 1rem;
            padding: 0.75rem 0.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-height: 140px;
        }

        .square-card i {
            font-size: 1.5rem;
        }

        .square-card .btn {
            margin-top: 0.25rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .square-card div.mt-2 {
            margin-top: 0.5rem !important;
            font-size: 0.85rem;
        }

        .bottom-nav .nav-link {
            font-size: 0.85rem;
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
        }

        .footer-nav .nav-link {
            text-align: center;
            font-size: 0.85rem;
        }
    </style>

    <!-- Header Info -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            @foreach($users as $user)
                <div class="d-flex align-items-center me-3">
                    <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" width="40" class="rounded-circle me-2">
                    <div>
                        <div>{{ $user->name }}</div>
                        <small>{{ $user->role }} | اعتبار: {{ $user->credit ?? 0 }} تومان</small>
                    </div>
                </div>
            @endforeach
        </div>
        <div><strong>sepasiran</strong></div>
        <div style="font-size: 24px;">&#9776;</div>
    </div>

    <!-- Search -->
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="جست‌وجو..." aria-label="Search">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
    </div>

    <!-- Notification -->
    <div class="card mb-4" style="border: 20px solid #0D47A1; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div class="card-header text-white" style="background-color: #0d47a1;">
            <strong>اطلاعیه</strong>
        </div>
        <div class="card-body bg-white text-dark text-left">
            لورم ایپسوم متن ساختگی جهت نمایش.
            <br>
            <small><i class="bi bi-calendar"></i> ۲۴ دی ۱۴۰۲</small>
        </div>
    </div>

    <!-- Shortcut Icons -->
    <div class="row text-center mb-4">
        @php
            $shortcuts = [
                ['icon' => 'bi-bag', 'label' => 'تگ‌های آماده فروش'],
                ['icon' => 'bi-people', 'label' => 'تعداد کل حیوانات'],
                ['icon' => 'bi-clock-history', 'label' => 'در انتظار خروج'],
            ];
        @endphp

        @foreach($shortcuts as $shortcut)
            <div class="col-4 mb-3">
                <div class="shortcut-icon">
                    <i class="bi {{ $shortcut['icon'] }} fs-4"></i>
                </div>
                <div class="shortcut-label mt-2">{{ $shortcut['label'] }}</div>
            </div>
        @endforeach
    </div>

    <!-- Metrics -->
    <div class="row text-center g-3">
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-truck fs-3"></i>
                <div class="mt-2">سفارشات در انتظار ارسال</div>
                <a href="#" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-cash-stack fs-3"></i>
                <div class="mt-2">مبالغ کل درآمد</div>
                <a href="#" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-list-check fs-3"></i>
                <div class="mt-2">تعداد سفارشات باز</div>
                <a href="#" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
    </div>

@endsection

@section('footer')
<nav class="footer-nav">
    <a class="nav-link" href="#"><i class="bi bi-house"></i><br>خانه</a>
    <a class="nav-link" href="#"><i class="bi bi-receipt"></i><br>سفارشات</a>
    <a class="nav-link" href="#"><i class="bi bi-plus-circle"></i><br>افزودن</a>
    <a class="nav-link" href="#"><i class="bi bi-gear"></i><br>مدیریت</a>
    <a class="nav-link" href="#"><i class="bi bi-person"></i><br>پروفایل</a>
</nav>
@endsection
