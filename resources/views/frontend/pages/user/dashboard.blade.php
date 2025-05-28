@extends('admin.layout')

@section('title', $title ?? 'داشبورد کاربر - Sepasiran')

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
   .
   

        .clinic-card {
    border: 1.5px solid #5472d3; /* lighter shade of #0d47a1 */
    border-radius: 8px;
    padding: 15px;
    background-color: #f8fafd; /* very light blue-ish background */
    box-shadow: 0 2px 5px rgba(13, 71, 161, 0.1); /* subtle shadow */
    transition: box-shadow 0.3s ease;
}

.clinic-card:hover {
    box-shadow: 0 4px 12px rgba(13, 71, 161, 0.25);
}

.clinic-photo {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 10px;
}

.clinic-info {
    color: #0d47a1;
}

.clinic-name {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 5px;
}

.clinic-address {
    font-size: 0.9rem;
    color: #555;
}

    }
</style>

<div class="container mt-3">

    {{-- User Info --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <img src="{{ $users->profile_picture ?? asset('images/default-user.png') }}" width="40" class="rounded-circle me-2" alt="User Picture">
            <div>
                <div>{{ $users->name }}</div>
                <small>اعتبار: {{ number_format($users->wallet ?? 0) }} تومان</small>
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
    <div class="row text-center g-3 mb-4">
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-clipboard2-check fs-3"></i>
                <div class="mt-2">تعداد سفارشات باز</div>
                <div class="h4 mt-1">{{ $openBookings ?? 0 }}</div>
                <a href="{{ route('breeder.dashboard') }}" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-cash-stack fs-3"></i>
                <div class="mt-2">سفارشات در انتظار ارسال</div>
                <div class="h4 mt-1">{{ number_format($clinicIncome ?? 0) }}</div>
                <a href="{{ route('wallet.index') }}" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
        <div class="col-4">
            <div class="square-card">
                <i class="bi bi-people fs-3"></i>
                <div class="mt-2">مبلغ کل درآمد</div>
                <div class="h4 mt-1">{{ $activeDoctors ?? 0 }}</div>
                <a href="{{ route('breeder.dashboard') }}" class="btn btn-light btn-sm">مشاهده</a>
            </div>
        </div>
    </div>
{{-- Nearby Clinics Card --}}
<div class="card mb-4">
    <div class="card-header text-white" style="background-color: #0d47a1;">
        <strong>کلینیک‌های نزدیک شما</strong>
    </div>
    <div class="card-body">
        @if($nearbyClinics->isEmpty())
            <p>کلینیکی در نزدیکی شما یافت نشد.</p>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                @foreach($nearbyClinics as $clinic)
                    <div class="col">
                        <div class="clinic-card h-100">
                            <img
                                src="{{ $clinic->profile_picture ?? asset('images/default-clinic.png') }}"
                                alt="{{ $clinic->name }}"
                                class="clinic-photo"
                            >
                            <div class="clinic-info">
                                <p class="clinic-name">{{ $clinic->name }} {{ $clinic->family }}</p>
                                <p class="clinic-address">{{ $clinic->address ?? 'آدرس ثبت نشده' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>


    {{-- Footer Navigation --}}
    <nav class="footer-nav">
        <a class="nav-link" href="#"><i class="bi bi-house"></i><br>خانه</a>
        <a class="nav-link" href="{{ route('breeder.dogs.listing') }}"><i class="bi bi-journal-text"></i><br>سگ ها</a>
        <a class="nav-link" href="{{ route('breeders.dashboard-create') }}"><i class="bi bi-plus-circle"></i><br>افزودن</a>
        <a class="nav-link" href="{{ route('wallet.index') }}"><i class="bi bi-gear"></i><br>کیف پول</a>
        <a class="nav-link" href="{{ route('breeder.profile') }}"><i class="bi bi-person"></i><br>پروفایل</a>
    </nav>

</div>
@endsection


