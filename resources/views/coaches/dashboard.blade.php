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
            <h4 class="mb-3">{{ $user->name }}</h4>
            @php $firstUser = $user->first(); @endphp
            @if ($firstUser)
                <div class="d-flex align-items-center flex-wrap">
                    <img src="{{ $firstUser->avatar_url ?? asset('images/default-avatar.png') }}" width="50" class="rounded-circle me-2 mb-2" alt="User Avatar">
                    <div>
                        <div>{{ $firstUser->name }}</div>
                        <small>{{ $firstUser->role ?? '' }} | اعتبار: {{ $firstUser->credit ?? 0 }} تومان</small>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-12 col-lg-4 text-lg-end text-center mt-3 mt-lg-0">
            <strong>Sepasiran</strong>
        </div>
    </div>

    <div class="input-group my-4">
        <input type="text" class="form-control" placeholder="جست‌وجو..." aria-label="Search">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
    </div>

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

    <h5 class="text-start mb-3">جلسات من</h5>

    <div class="card shadow-sm mb-5" style="background-color: #0d47a1; padding: 20px; border-radius: 12px;">
        <div class="bg-white p-4" style="border-radius: 12px;">
            @forelse($todaysAppointments as $appointment)
                <div class="row g-0 align-items-center mb-3" style="border: 1px solid #ccc; border-radius: 12px; overflow: hidden;">
                    <div class="col-12 col-md-9 p-3 text-end">
                        <span class="badge bg-success mb-2">امروز</span>
                        <h5 class="card-title">ملاقات با {{ $appointment->user->name ?? 'کاربر ناشناس' }}</h5>
                        <p class="card-text text-muted small">
                            نوع خدمت: {{ $appointment->service_type }} <br>
                            ساعت: {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}
                        </p>
                        <div class="row text-muted small">
                            <div class="col-md-6">وضعیت: {{ $appointment->status }}</div>
                            <div class="col-md-6">قیمت: {{ number_format($appointment->price) }} تومان</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 text-center p-3">
                        <img src="{{ $appointment->user->photo ?? 'default.jpg' }}" class="img-fluid rounded-end" alt="user image" style="max-height: 100px;">
                    </div>
                </div>
            @empty
                <p class="text-center m-0">هیچ قراری برای امروز ثبت نشده است.</p>
            @endforelse
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h5 class="mb-0">قرارهای امروز</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>کاربر</th>
                            <th>نوع خدمت</th>
                            <th>ساعت</th>
                            <th>وضعیت</th>
                            <th>قیمت</th>
                            <th>تصویر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($todaysAppointments as $appointment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $appointment->user->name ?? 'کاربر ناشناس' }}</td>
                                <td>{{ $appointment->service_type }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}</td>
                                <td>{{ $appointment->status }}</td>
                                <td>{{ number_format($appointment->price) }} تومان</td>
                                <td>
                                    @if($appointment->user?->photo)
                                        <img src="{{ asset($appointment->user->photo) }}" alt="user image" style="max-height: 50px;">
                                    @else
                                        ندارد
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">هیچ قراری برای امروز ثبت نشده است.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row text-center mb-4">
        @php
            $shortcuts = [
                ['icon' => 'bi-activity', 'label' => 'پیش‌نویس گزارش', 'url' => route('coaches.dashboard')],
                ['icon' => 'bi-journal-medical', 'label' => 'سابقه ویزیت', 'url' => route('coaches.dashboard')],
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

    <h5 class="text-start mb-3">دوره های من</h5>

    <div class="card shadow-sm mb-5" style="background-color: #0d47a1; padding: 20px; border-radius: 12px;">
        <div class="row g-0 align-items-center mb-3 bg-white" style="border-radius: 12px; overflow: hidden;">
            <div class="col-12 col-md-9 p-3 text-end">
                <span class="badge bg-primary mb-2">فعال</span>
                <h5 class="card-title">دوره حفاظت و نگهبانی</h5>
                <p class="card-text text-muted small">
                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است
                </p>
                <div class="row text-muted small">
                    <div class="col-md-3">قیمت دوره: 100,000 تومان</div>
                    <div class="col-md-3">تعداد شرکت‌کنندگان: 35 نفر</div>
                    <div class="col-md-3">تاریخ شروع و پایان: 3 شهریور 1403 تا 15 آبان 1403</div>
                    <div class="col-md-3">وضعیت دوره: در حال برگزاری</div>
                </div>
            </div>
            <div class="col-12 col-md-3 text-center p-3">
                <img src="your-image.jpg" class="img-fluid rounded-end" alt="course image" style="max-height: 100px;">
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="#" class="text-white text-decoration-none">مشاهده بیشتر ^</a>
        </div>
    </div>

        <nav class="footer-nav">
            <a class="nav-link" href="{{ route('coaches.dashboard') }}">
                <i class="bi bi-house fs-4" style="color:#000;"></i><br>خانه
            </a>
            <a class="nav-link" href="{{ route('coaches.listing', ['coachId' => $coachId]) }}">
                <i class="bi bi-receipt fs-4" style="color:#000;"></i><br>سفارشات
            </a>
            <a class="nav-link" href="{{ route('coaches.profile.edit') }}">
                <i class="bi bi-plus-circle fs-4" style="color:#000;"></i><br>افزودن
            </a>
            <a class="nav-link" href="{{ route('wallet.index') }}">
                <i class="bi bi-wallet2 fs-4" style="color: inherit;"></i><br>مدیریت
            </a>

        </nav>


</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    z-index: 1001;
    padding: 0.5rem 0;
    height: 60px; /* fixed height */
    box-sizing: border-box;
}
.footer-nav {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #f8f9fa;
    border-top: 1px solid #ccc;
    display: flex;
    z-index: 1001;
    padding: 0.5rem 0;
    height: 60px; /* fixed height */
    box-sizing: border-box;
    overflow: visible; /* ensure icons are not clipped */
}

.footer-nav .nav-link {
    flex: 1; /* each link takes equal width */
    min-width: 60px; /* ensures enough space for icons */
    text-align: center;
    font-size: 0.85rem;
    color: #000;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: visible; /* avoid clipping icons */
}

.footer-nav .nav-link i {
    font-size: 1.5rem; /* slightly bigger icons */
    line-height: 1;
    margin-bottom: 0.15rem;
    color: inherit; /* explicitly inherit color */
}

.footer-nav .nav-link:hover,
.footer-nav .nav-link:focus {
    color: #0d47a1;
}

body {
    padding-bottom: 70px; /* extra space for fixed footer */
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
        width: 80px;
        height: 80px;
    }
    .footer-nav .nav-link {
        font-size: 0.75rem;
    }
}

    }
</style>
@endpush
