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
    ];
@endphp
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
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
                ['label' => 'موجودی کیف پول', 'value' => number_format($stats['wallet']) . ' ت', 'hint' => 'قابل استفاده در رزرو', 'icon' => 'fas fa-wallet'],
                ['label' => 'رزروهای فعال', 'value' => number_format($stats['upcoming']), 'hint' => 'در صف پیگیری', 'icon' => 'far fa-calendar-check'],
                ['label' => 'سگ‌های من', 'value' => number_format($stats['dogs']), 'hint' => 'ثبت‌شده در حساب', 'icon' => 'fas fa-paw'],
            ],
        ])

        @include('frontend.partials.dashboard-finance-chart', [
            'financeChart' => $financeChart,
            'chartTitle' => 'نمودار تراکنش‌های مالی',
            'chartId' => 'spCustomerFinanceChart',
        ])

        @include('frontend.partials.dashboard-actions', [
            'actions' => [
                ['label' => 'رزرو خدمت', 'hint' => 'مربی، کلینیک و بیشتر', 'url' => route('frontend.booking.index'), 'icon' => 'far fa-calendar-plus'],
                ['label' => 'کیف پول', 'hint' => 'موجودی و تراکنش‌ها', 'url' => route('wallet.index'), 'icon' => 'fas fa-wallet'],
                ['label' => 'مرور سگ‌ها', 'hint' => 'بازار و عرضه', 'url' => route('frontend.dog.supply.index'), 'icon' => 'fas fa-dog'],
                ['label' => 'خانه', 'hint' => 'بازگشت به صفحه اصلی', 'url' => route('frontend.home'), 'icon' => 'fas fa-home'],
            ],
        ])

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>رزروهای پیشِ رو</h2>
            </header>
            <div class="sp-dash__list">
                @forelse($upcomingBookings as $booking)
                    @php [$stLabel, $stClass] = $statusMap[$booking->status] ?? [$booking->status, 'is-pending']; @endphp
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="far fa-clock"></i></span>
                        <div>
                            <strong>{{ $booking->service_type }}</strong>
                            <p class="sp-dash__muted latin">{{ $booking->booking_code }}</p>
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
