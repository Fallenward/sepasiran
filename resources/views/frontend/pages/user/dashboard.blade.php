@extends('frontend.layouts.app')

@section('title', 'داشبورد من — سپاسیران')

@section('content')
@php
    $statusMap = [
        'PENDING' => ['در انتظار', 'is-pending'],
        'OPEN' => ['باز', 'is-pending'],
        'QUOTED' => ['پیشنهاد شده', 'is-pending'],
        'CONFIRMED' => ['تأیید شده', 'is-active'],
        'COMPLETED' => ['انجام شده', 'is-done'],
        'CANCELLED' => ['لغو شده', 'is-danger'],
        'CANCELED' => ['لغو شده', 'is-danger'],
        'REJECTED' => ['رد شده', 'is-danger'],
        'ACCEPTED' => ['پذیرفته', 'is-active'],
    ];
@endphp
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
                @include('frontend.partials.dashboard-nav')
@include('frontend.partials.dashboard-hero', [
            'user' => $user,
            'roleLabel' => $roleLabel,
            'lede' => 'رزروها، سگ‌ها و کیف پول‌تان را از یک صفحه مدیریت کنید.',
            'ctaTitle' => 'رزرو خدمت',
            'ctaHint' => 'مربی، کلینیک یا خدمات دیگر را در چند قدم رزرو کنید.',
            'primaryAction' => ['label' => 'رزرو جدید', 'url' => route('frontend.booking.index')],
        ])

        @include('frontend.partials.dashboard-metrics', [
            'metrics' => [
                ['label' => 'موجودی کیف پول', 'value' => number_format($stats['wallet']) . ' ت', 'hint' => 'قابل استفاده در رزرو', 'icon' => 'fas fa-wallet', 'url' => route('wallet.index')],
                ['label' => 'رزروهای فعال', 'value' => number_format($stats['upcoming']), 'hint' => 'در صف پیگیری', 'icon' => 'far fa-calendar-check', 'url' => route('account.bookings.index')],
                ['label' => 'سگ‌های من', 'value' => number_format($stats['dogs']), 'hint' => 'ثبت‌شده در حساب', 'icon' => 'fas fa-paw', 'url' => route('account.dogs.index')],
            ],
        ])

        @include('frontend.partials.dashboard-finance-chart', [
            'financeChart' => $financeChart,
            'chartTitle' => 'نمودار تراکنش‌های مالی',
            'chartId' => 'spCustomerFinanceChart',
        ])

        
        @include('frontend.partials.dashboard-notifications', [
            'dashNotifications' => $dashNotifications ?? collect(),
        ])

@include('frontend.partials.dashboard-actions', [
            'actions' => [
                ['label' => 'رزروهای من', 'hint' => 'پیگیری درخواست‌های رزرو', 'url' => route('account.bookings.index'), 'icon' => 'far fa-calendar-alt'],
                ['label' => 'درخواست‌های باز', 'hint' => 'تابلوی درخواست مشتری', 'url' => route('frontend.booking.open'), 'icon' => 'fas fa-bullhorn'],
                ['label' => 'سگ‌های من', 'hint' => 'افزودن و مدیریت سگ', 'url' => route('account.dogs.index'), 'icon' => 'fas fa-paw'],
                ['label' => 'رزرو جدید', 'hint' => 'مربی، کلینیک و بیشتر', 'url' => route('frontend.booking.index'), 'icon' => 'far fa-calendar-plus'],
                ['label' => 'جلسات باز', 'hint' => 'بازار جلسات ارائه‌دهندگان', 'url' => route('frontend.open-sessions.index'), 'icon' => 'fas fa-door-open'],
                ['label' => 'جلسات باز من', 'hint' => 'ایجاد / اپلای‌ها', 'url' => route('frontend.open-sessions.mine'), 'icon' => 'fas fa-comments'],
                ['label' => 'کیف پول', 'hint' => 'موجودی و تراکنش‌ها', 'url' => route('wallet.index'), 'icon' => 'fas fa-wallet'],
                ['label' => 'ویرایش پروفایل', 'hint' => 'تصویر و اطلاعات حساب', 'url' => route('account.profile.edit'), 'icon' => 'far fa-user'],
            ],
        ])

        @include('frontend.partials.dashboard-my-dogs', ['myDogs' => $myDogs ?? collect()])

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>درخواست‌های رزرو من</h2>
                <a href="{{ route('account.bookings.index') }}">مشاهده همه</a>
            </header>
            <div class="sp-dash__list">
                @forelse($upcomingBookings as $booking)
                    @php [$stLabel, $stClass] = $statusMap[$booking->status] ?? [$booking->status, 'is-pending']; @endphp
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="far fa-clock"></i></span>
                        <div>
                            <strong>{{ $booking->service_type }}</strong>
                            <p class="sp-dash__muted">{{ $booking->booking_code }}</p>
                        </div>
                        <div class="sp-dash__row-meta">
                            <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
                            <span class="sp-dash__muted">{{ optional($booking->appointment_date ?? $booking->preferred_date)->format('Y/m/d') ?? '—' }}</span>
                        </div>
                    </article>
                @empty
                    <p class="sp-dash__empty-inline">موردی برای نمایش نیست.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
