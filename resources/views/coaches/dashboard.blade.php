@extends('frontend.layouts.app')

@section('title', ($title ?? 'داشبورد مربی') . ' — سپاسیران')

@section('content')
@php
    $statusMap = [
        'PENDING' => ['در انتظار', 'is-pending'],
        'OPEN' => ['باز', 'is-pending'],
        'QUOTED' => ['پیشنهاد شده', 'is-pending'],
        'CONFIRMED' => ['تأیید شده', 'is-active'],
        'COMPLETED' => ['انجام شده', 'is-done'],
        'CANCELLED' => ['لغو شده', 'is-danger'],
    ];
@endphp
<div class="sp-dash sp-dash--coach">
    <div class="sp-dash__inner">
                @include('frontend.partials.dashboard-nav')
@include('frontend.partials.dashboard-hero', [
            'user' => $user,
            'roleLabel' => $roleLabel ?? 'مربی',
            'lede' => 'جلسات امروز و درخواست‌های باز را از همین‌جا مدیریت کنید.',
            'ctaTitle' => 'پروفایل مربی',
            'ctaHint' => 'نام، تخصص و شهر را برای نمایش عمومی به‌روز نگه دارید.',
            'primaryAction' => ['label' => 'ویرایش پروفایل', 'url' => route('account.profile.edit')],
        ])

        @include('frontend.partials.dashboard-metrics', [
            'metrics' => [
                ['label' => 'جلسات امروز', 'value' => number_format($todaysAppointments->count()), 'hint' => 'برنامه روزانه', 'icon' => 'fas fa-sun'],
                ['label' => 'درخواست‌های باز', 'value' => number_format($pendingBookings ?? 0), 'hint' => 'نیاز به پاسخ', 'icon' => 'fas fa-inbox', 'url' => route('account.bookings.index', ['tab' => 'inbox'])],
                ['label' => 'سگ‌های من', 'value' => number_format(($myDogs ?? collect())->count()), 'hint' => 'ثبت‌شده در حساب', 'icon' => 'fas fa-paw', 'url' => route('account.dogs.index')],
                ['label' => 'دوره‌ها', 'value' => number_format($teacherCourses->count()), 'hint' => 'منتشرشده', 'icon' => 'fas fa-graduation-cap', 'url' => route('coaches.courses.index')],
            ],
        ])

        
        @include('frontend.partials.dashboard-finance-chart', [
            'financeChart' => $financeChart,
            'chartTitle' => 'نمودار تراکنش‌های مالی',
            'chartId' => 'spCoachFinanceChart',
        ])


        @include('frontend.partials.dashboard-notifications', [
            'dashNotifications' => $dashNotifications ?? collect(),
        ])

@include('frontend.partials.dashboard-actions', [
            'actions' => [
                ['label' => 'سگ‌های من', 'hint' => 'افزودن و مدیریت سگ‌های حساب', 'url' => route('account.dogs.index'), 'icon' => 'fas fa-paw'],
                ['label' => 'درخواست‌های رزرو', 'hint' => 'صندوق درخواست مشتریان', 'url' => route('account.bookings.index', ['tab' => 'inbox']), 'icon' => 'fas fa-inbox'],
                ['label' => 'دوره‌های من', 'hint' => 'افزودن و ویرایش دوره', 'url' => route('coaches.courses.index'), 'icon' => 'fas fa-graduation-cap'],
                ['label' => 'فضای مربی‌گری', 'hint' => 'نمای کلی دوره‌ها', 'url' => route('coaches.listing'), 'icon' => 'fas fa-chalkboard-teacher'],
                ['label' => 'خدمات رزرو', 'hint' => 'قیمت، مدت، تخصص', 'url' => route('account.services.index'), 'icon' => 'fas fa-briefcase'],
                ['label' => 'جلسات باز من', 'hint' => 'ایجاد و مدیریت جلسه باز', 'url' => route('frontend.open-sessions.mine'), 'icon' => 'fas fa-door-open'],
                ['label' => 'زمان‌بندی', 'hint' => 'ساعات کاری و مسدودسازی', 'url' => route('account.availability.edit'), 'icon' => 'fas fa-calendar-alt'],
                ['label' => 'قیمت خدمات', 'hint' => 'مبلغ جلسه رزرو را خودتان تعیین کنید', 'url' => route('account.service-prices.edit'), 'icon' => 'fas fa-tags'],
                ['label' => 'کیف پول', 'hint' => 'دریافت‌ها و موجودی', 'url' => route('wallet.index'), 'icon' => 'fas fa-wallet'],
                ['label' => 'پروفایل', 'hint' => 'نام، تخصص، شهر', 'url' => route('account.profile.edit'), 'icon' => 'far fa-user'],
            ],
        ])

        @include('frontend.partials.dashboard-my-dogs', ['myDogs' => $myDogs ?? collect()])

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>جلسات امروز</h2>
            </header>
            <div class="sp-dash__list">
                @forelse($todaysAppointments as $appointment)
                    @php [$stLabel, $stClass] = $statusMap[$appointment->status] ?? [$appointment->status, 'is-pending']; @endphp
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="fas fa-user"></i></span>
                        <div>
                            <strong>{{ $appointment->user->name ?? 'کاربر' }} {{ $appointment->user->family ?? '' }}</strong>
                            <p class="sp-dash__muted">{{ $appointment->service_type }} · {{ optional($appointment->appointment_date)->format('H:i') ?? '—' }}</p>
                        </div>
                        <div class="sp-dash__row-meta">
                            <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
                            <span class="sp-dash__muted"><span class="num">{{ number_format((float) ($appointment->total ?? $appointment->price ?? 0)) }}</span> ت</span>
                        </div>
                    </article>
                @empty
                    <div class="sp-dash__empty">
                        <div class="sp-dash__empty-visual" aria-hidden="true"></div>
                        <p>جلسه‌ای برای امروز ثبت نشده است.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>درخواست‌های رزرو</h2>
                <a href="{{ route('account.bookings.index', ['tab' => 'inbox']) }}">مشاهده همه</a>
            </header>
            <div class="sp-dash__list">
                @forelse($upcoming as $booking)
                    @php [$stLabel, $stClass] = $statusMap[$booking->status] ?? [$booking->status, 'is-pending']; @endphp
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="far fa-envelope"></i></span>
                        <div>
                            <strong>{{ $booking->user->name ?? 'کاربر' }}</strong>
                            <p class="sp-dash__muted">{{ $booking->booking_code }}</p>
                        </div>
                        <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
                    </article>
                @empty
                    <p class="sp-dash__empty-inline">درخواستی در صف نیست.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
