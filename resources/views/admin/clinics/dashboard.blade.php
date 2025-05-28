@extends('admin.layout')

@section('title', 'داشبورد کلینیک - Sepasiran')

@section('header')
    <header class="custom-header d-flex align-items-center justify-content-center">
        <h5 class="text-white m-0">{{ $title }}</h5>
    </header>
@endsection

@section('content')
<style>
    body {
        padding-bottom: 80px;
        background-color: #f8f9fa;
        font-family: Tahoma, sans-serif;
        direction: rtl;
        text-align: right;
    }

    .custom-header {
        position: fixed;
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
        background-color: #0d47a1;
        color: #fff;
        padding: 20px;
        border-radius: 50%;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        transition: background-color 0.3s;
    }

    .shortcut-icon:hover {
        background-color: #1565c0;
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
        z-index: 1001;
    }

    .footer-nav .nav-link {
        text-align: center;
        font-size: 0.85rem;
    }
</style>

<div class="container mt-5 pt-4">

    <!-- Clinic Info -->
    <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
        <div style="font-size: 24px;">&#9776;</div>
        <div><strong>Sepasiran</strong></div>
        <div class="d-flex align-items-center">
            @foreach($clinics as $clinic)
                <div class="d-flex align-items-center ms-3">
                    <img src="{{ $clinic->logo_url ?? asset('images/default-clinic.png') }}" width="40" class="rounded-circle ms-2">
                    <div>
                        <div>{{ $clinic->name }}</div>
                        <small>اعتبار: {{ number_format($clinic->credit ?? 0) }} تومان</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Search -->
    <div class="input-group mb-3">
        <input type="text" class="form-control text-end" placeholder="جست‌وجو..." aria-label="Search">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
    </div>

    <!-- Notifications -->
    <div class="card mb-4" style="border: 2px solid #0d47a1; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div class="card-header text-white" style="background-color: #0d47a1;">
            <strong>اطلاعیه‌ها</strong>
        </div>
        <div class="card-body bg-white text-dark text-end">
            @forelse($notifications as $notification)
                <div class="mb-2 border-bottom pb-2">
                    {{ $notification['message'] }}
                    <br>
                    <small><i class="bi bi-calendar"></i>
                        {{ \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $notification['date'])->format('d M Y') }}
                    </small>
                </div>
            @empty
                <p class="text-muted">هیچ اطلاعیه‌ای موجود نیست.</p>
            @endforelse
        </div>
    </div>

    <!-- Shortcut Icons -->
    <div class="row text-center mb-4">
        @php
            $shortcuts = [
                ['icon' => 'bi-activity', 'label' => 'پیش‌نویس گزارش', 'link' => route('clinic.dashboard')],
                ['icon' => 'bi-journal-medical', 'label' => 'سابقه ویزیت', 'link' => route('clinic.dashboard')],
                ['icon' => 'bi-cash-coin', 'label' => 'تسویه حساب‌ها', 'link' => route('clinic.dashboard')],
            ];
        @endphp

        @foreach($shortcuts as $shortcut)
            <div class="col-4 mb-3">
                <a href="{{ $shortcut['link'] }}" class="text-decoration-none text-dark">
                    <div class="shortcut-icon">
                        <i class="bi {{ $shortcut['icon'] }} fs-4"></i>
                    </div>
                    <div class="shortcut-label mt-2">{{ $shortcut['label'] }}</div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Metrics -->
    <div class="row text-center g-3">
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-clipboard2-check fs-3"></i>
                <div class="mt-2">ویزیت‌های فعال</div>
                <div>{{ number_format($activeVisits) }}</div>
                <a href="{{ route('clinic.dashboard') }}" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-cash-stack fs-3"></i>
                <div class="mt-2">درآمد کلینیک</div>
                <div>{{ number_format($clinicIncome) }} تومان</div>
                <a href="{{ route('clinic.dashboard') }}" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-people fs-3"></i>
                <div class="mt-2">پزشکان فعال</div>
                <div>{{ number_format($activeDoctors) }}</div>
                <a href="{{ route('clinic.dashboard') }}" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
    </div>
    <nav class="footer-nav">
    <a class="nav-link" href="#"><i class="bi bi-house"></i><br>خانه</a>
    <a class="nav-link" href="{{ route('clinic.profile.edit') }}"><i class="bi bi-journal-text">جلسات   </i><br></a>
    <a class="nav-link" href="{{ route('wallet.index') }}"><i class="bi bi-plus-circle"></i><br>کیف پول</a>
    <a class="nav-link" href="{{ route('linic.profile.edit') }}"><i class="bi bi-person"></i><br>پروفایل</a>
</nav>
</div>
@endsection


