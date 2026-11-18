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
                ['label' => 'درخواست‌های باز', 'value' => number_format($pendingBookings ?? 0), 'hint' => 'نیاز به پاسخ', 'icon' => 'fas fa-inbox'],
                ['label' => 'دوره‌ها', 'value' => number_format($teacherCourses->count()), 'hint' => 'منتشرشده', 'icon' => 'fas fa-graduation-cap'],
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
                ['label' => 'کیف پول مربی', 'hint' => 'دریافت‌ها و موجودی', 'url' => route('wallet.index'), 'icon' => 'fas fa-wallet'],
                ['label' => 'لیست مربی‌گری', 'hint' => 'نمایش عمومی', 'url' => route('coaches.listing'), 'icon' => 'fas fa-list'],
                ['label' => 'پروفایل', 'hint' => 'نام، تخصص، شهر', 'url' => route('account.profile.edit'), 'icon' => 'far fa-user'],
                ['label' => 'خانه', 'hint' => 'بازگشت به فروشگاه', 'url' => route('frontend.home'), 'icon' => 'fas fa-home'],
            ],
        ])

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
                            <span class="sp-dash__muted latin">{{ number_format((float) ($appointment->total ?? $appointment->price ?? 0)) }} ت</span>
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
                <h2>درخواست‌های پیشِ رو</h2>
            </header>
            <div class="sp-dash__list">
                @forelse($upcoming as $booking)
                    @php [$stLabel, $stClass] = $statusMap[$booking->status] ?? [$booking->status, 'is-pending']; @endphp
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="far fa-envelope"></i></span>
                        <div>
                            <strong>{{ $booking->user->name ?? 'کاربر' }}</strong>
                            <p class="sp-dash__muted latin">{{ $booking->booking_code }}</p>
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
