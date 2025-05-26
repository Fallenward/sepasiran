@extends('admin.layout')

@section('title', $title ?? 'داشبورد کلینیک - Sepasiran')

@section('content')
<style>
    body {
        padding-bottom: 80px;
        background-color: #f8f9fa;
        font-family: Tahoma, sans-serif;
    }
    .navbar {
        background-color: #0d47a1 !important;
    }
    .navbar .nav-link, .navbar-brand, .navbar .btn {
        color: #fff !important;
    }
    .navbar .btn:hover {
        color: #0d47a1 !important;
        background-color: #fff !important;
    }
    .custom-header {
        background-color: #0d47a1;
        color: white;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #ccc;
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
    .square-card .btn {
        margin-top: 0.25rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
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



{{-- Main Content --}}
<div class="container mt-3">

    {{-- User Info --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <img src="{{ $users->logo_url ?? asset('images/default-clinic.png') }}" width="40" class="rounded-circle me-2" alt="لوگو کلینیک">
            <div>
                <div>{{ $users->name }}</div>
                <small>اعتبار: {{ number_format($users->credit ?? 0) }} تومان</small>
            </div>
        </div>
        <div><strong>Sepasiran</strong></div>
        <div style="font-size: 24px; cursor: pointer;">&#9776;</div>
    </div>

    {{-- Search Bar --}}
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="جست‌وجو..." aria-label="Search">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
    </div>

    {{-- Announcement Card --}}
    <div class="card mb-4" style="border: 20px solid #0D47A1; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div class="card-header text-white" style="background-color: #0d47a1;">
            <strong>اطلاعیه</strong>
        </div>
        <div class="card-body bg-white text-dark text-left">
            اطلاعیه ویژه مربوط به کلینیک.
            <br>
            <small><i class="bi bi-calendar"></i> {{ \Morilog\Jalali\Jalalian::now()->format('d F Y') }}</small>
        </div>
    </div>

    {{-- Shortcut Icons --}}
    <div class="row text-center mb-4">
        @php
            $shortcuts = [
                ['icon' => 'bi-activity', 'label' => 'پیش‌نویس گزارش', 'url' => route('breeder.dashboard')],
                ['icon' => 'bi-journal-medical', 'label' => 'سابقه ویزیت', 'url' => route('breeder.dashboard')],
                ['icon' => 'bi-cash-coin', 'label' => 'تسویه حساب‌ها', 'url' => route('wallet.index')],
            ];
        @endphp

        @foreach($shortcuts as $shortcut)
            <div class="col-4 mb-3">
                <a href="{{ $shortcut['url'] }}" class="text-decoration-none text-dark">
                    <div class="shortcut-icon">
                        <i class="bi {{ $shortcut['icon'] }} fs-4"></i>
                    </div>
                    <div class="shortcut-label mt-2">{{ $shortcut['label'] }}</div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Dashboard Cards --}}
    <div class="row text-center g-3">
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-clipboard2-check fs-3"></i>
                <div class="mt-2">ویزیت‌های فعال</div>
                <div class="h4 mt-1">{{ $openBookings }}</div>
                <a href="{{ route('breeder.dashboard') }}" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-cash-stack fs-3"></i>
                <div class="mt-2">درآمد کلینیک</div>
                <div class="h4 mt-1">{{ number_format($clinicIncome) }} تومان</div>
                <a href="{{ route('wallet.index') }}" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-people fs-3"></i>
                <div class="mt-2">پزشکان فعال</div>
                <div class="h4 mt-1">{{ $activeDoctors }}</div>
                <a href="{{ route('breeder.dashboard') }}" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
    </div>

    {{-- Footer Navigation --}}
    <nav class="footer-nav">
        <a class="nav-link" href="#"><i class="bi bi-house"></i><br>خانه</a>
        <a class="nav-link" href="#"><i class="bi bi-journal-text"></i><br>ویزیت‌ها</a>
        <a class="nav-link" href="#"><i class="bi bi-plus-circle"></i><br>افزودن</a>
        <a class="nav-link" href="#"><i class="bi bi-gear"></i><br>تنظیمات</a>
        <a class="nav-link" href="#"><i class="bi bi-person"></i><br>پروفایل</a>
    </nav>
</div>
@endsection
