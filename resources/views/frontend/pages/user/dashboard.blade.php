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
<div class="sp-dash">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-hero', [
            'user' => $user,
            'roleLabel' => $roleLabel,
            'lede' => 'رزروها، سگ‌های شما و کیف پول — در یک نگاه.',
            'primaryAction' => ['label' => 'رزرو جدید', 'url' => route('frontend.booking.index')],
        ])

        @include('frontend.partials.dashboard-metrics', [
            'metrics' => [
                ['label' => 'موجودی کیف پول', 'value' => number_format($stats['wallet']) . ' ت', 'hint' => 'قابل استفاده در رزرو'],
                ['label' => 'رزروهای فعال', 'value' => number_format($stats['upcoming']), 'hint' => 'در صف پیگیری'],
                ['label' => 'سگ‌های من', 'value' => number_format($stats['dogs']), 'hint' => 'ثبت‌شده در حساب'],
            ],
        ])

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>اقدام بعدی</h2>
            </header>
            @if($nextBooking)
                @php [$stLabel, $stClass] = $statusMap[$nextBooking->status] ?? [$nextBooking->status, 'is-pending']; @endphp
                <div class="sp-dash__next">
                    <div>
                        <p class="sp-dash__next-kicker">نزدیک‌ترین رزرو</p>
                        <h3>{{ $nextBooking->service_type }} · {{ $nextBooking->booking_code }}</h3>
                        <p class="sp-dash__muted">
                            {{ optional($nextBooking->appointment_date ?? $nextBooking->preferred_date)->format('Y/m/d') ?? 'زمان اعلام نشده' }}
                            @if($nextBooking->slot) · {{ $nextBooking->slot }} @endif
                        </p>
                    </div>
                    <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
                </div>
            @else
                <div class="sp-dash__empty">
                    <p>هنوز رزروی ندارید.</p>
                    <a class="sp-btn sp-btn--primary" href="{{ route('frontend.booking.index') }}">شروع رزرو</a>
                </div>
            @endif
        </section>

        @include('frontend.partials.dashboard-actions', [
            'actions' => [
                ['label' => 'رزرو خدمت', 'hint' => 'مربی، کلینیک و بیشتر', 'url' => route('frontend.booking.index')],
                ['label' => 'کیف پول', 'hint' => 'موجودی و تراکنش‌ها', 'url' => route('wallet.index')],
                ['label' => 'مرور سگ‌ها', 'hint' => 'بازار و عرضه', 'url' => route('frontend.dog.supply.index')],
                ['label' => 'خانه', 'hint' => 'بازگشت به صفحه اصلی', 'url' => route('frontend.home')],
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
