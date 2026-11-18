@extends('admin.layout')

@section('title', 'لیست سگ‌ها - Sepasiran')

@section('header')
<header class="custom-header d-flex align-items-center justify-content-center">
    <h5 class="text-white m-0">سگ‌های ثبت‌شده</h5>
</header>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Dana&display=swap" rel="stylesheet">
<style>
    body {
        direction: rtl;
        font-family: 'Dana', Tahoma, sans-serif;
        background-color: #f9f9f9;
        padding-bottom: 80px;
    }
    .custom-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 58px;
        background-color: #0d47a1;
        color: white;
        z-index: 1000;
        border-bottom: 1px solid #ccc;
        padding: 0 1rem;
    }
    .container {
        margin-top: 80px;
    }
    .footer-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        background: white;
        border-top: 1px solid #ddd;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
    }
    .footer-nav .nav-link {
        text-align: center;
        font-size: 14px;
        color: #333;
    }
    .footer-nav .nav-link i {
        font-size: 20px;
        display: block;
    }
    .profile-pic {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
    @media (max-width: 768px) {
        .table th, .table td {
            font-size: 12px;
            padding: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 mb-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ $breeder->profile_image_url ?? '/default.jpg' }}" class="profile-pic" alt="پروفایل">
            <div class="me-2">
                <strong>{{ $breeder->name ?? 'بدون نام' }}</strong><br>
                <small>{{ $breeder->title ?? 'پرورش دهنده سگ' }}</small>
            </div>
        </div>
        <h4 class="text-primary fw-bold">Sepasiran</h4>
    </div>

    <!-- Filters -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-6 col-md-2">
            <select name="breed" class="form-select">
                <option value="">نژاد سگ</option>
                <option value="doberman">دوبرمن</option>
                <option value="rottweiler">روتوایلر</option>
                <!-- Add more options if needed -->
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="gender" class="form-select">
                <option value="">جنسیت</option>
                <option value="male">نر</option>
                <option value="female">ماده</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="age" class="form-select">
                <option value="">سن</option>
                <option value="1">1 سال</option>
                <option value="2">2 سال</option>
                <!-- Add more if needed -->
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="vaccine" class="form-select">
                <option value="">واکسیناسیون</option>
                <option value="done">واکسینه شده</option>
                <option value="not_done">نشده</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="sold" class="form-select">
                <option value="">وضعیت فروش</option>
                <option value="1">فروخته شده</option>
                <option value="0">در حال فروش</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <button class="btn btn-primary w-100">نمایش نتایج</button>
        </div>
    </form>

    <!-- Dog List -->
    <h6 class="mb-2">لیست سگ‌های ثبت شده قبلی</h6>
    <div class="table-responsive">
        <table class="table table-bordered text-center bg-white">
            <thead class="table-light">
                <tr>
                    <th>نام سگ</th>
                    <th>نژاد</th>
                    <th>سن</th>
                    <th>واکسیناسیون</th>
                    <th>فروش</th>
                    <th>قیمت</th>
                    <th>اقدام</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dogs as $dog)
                    <tr>
                        <td>{{ $dog->name }}</td>
                        <td>{{ $dog->breed->name ?? '-' }}</td>
                        <td>{{ $dog->age }} سال</td>
                        <td>
                            <span class="badge bg-{{ $dog->vaccinated ? 'success' : 'secondary' }}">
                                {{ $dog->vaccinated ? 'واکسینه شده' : 'نشده' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $dog->sold ? 'danger' : 'success' }}">
                                {{ $dog->sold ? 'فروخته شد' : 'در حال فروش' }}
                            </span>
                        </td>
                        <td>{{ number_format($dog->price) }}</td>
                        <td>
                            <form method="POST" action="{{ route('breeder.dogs.delete', $dog->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">هیچ سگی یافت نشد</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('breeder.dashboard-create') }}" class="btn btn-primary mt-3">افزودن سگ جدید</a>

</div>
@endsection

@section('footer')
<nav class="footer-nav">
    <a class="nav-link" href="{{ route('breeder.dashboard') }}">
        <i class="bi bi-house"></i><br>داشبورد
    </a>
    <a class="nav-link" href="#">
        <i class="bi bi-receipt"></i><br>تراکنش‌ها
    </a>
    <a class="nav-link" href="#">
        <i class="bi bi-plus-circle"></i><br>ثبت سفارش
    </a>
    <a class="nav-link" href="{{ route('profile') }}">
        <i class="bi bi-person"></i><br>پروفایل
    </a>
</nav>
@endsection
