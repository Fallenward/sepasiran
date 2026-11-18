@extends('frontend.layouts.app')

@section('title', ($title ?? 'داشبورد کلینیک') . ' — سپاسیران')

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
<div class="sp-dash">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-hero', [
            'user' => $user,
            'roleLabel' => $roleLabel ?? 'کلینیک',
            'lede' => 'نوبت‌های فعال، درآمد تکمیل‌شده و مدیریت پروفایل کلینیک.',
            'primaryAction' => ['label' => 'ویرایش پروفایل', 'url' => route('clinic.profile.edit')],
        ])

        @include('frontend.partials.dashboard-metrics', [
            'metrics' => [
                ['label' => 'نوبت‌های تأییدشده', 'value' => number_format($activeVisits), 'hint' => 'در حال انجام'],
                ['label' => 'در صف', 'value' => number_format($pendingVisits ?? 0), 'hint' => 'منتظر تأیید'],
                ['label' => 'درآمد تکمیل‌شده', 'value' => number_format($clinicIncome) . ' ت', 'hint' => 'جمع سرویس‌های انجام‌شده'],
            ],
        ])

        @include('frontend.partials.dashboard-actions', [
            'actions' => [
                ['label' => 'پروفایل کلینیک', 'hint' => 'آدرس و اطلاعات تماس', 'url' => route('clinic.profile.edit')],
                ['label' => 'کیف پول', 'hint' => 'موجودی حساب', 'url' => route('wallet.index')],
                ['label' => 'خانه', 'hint' => 'بازگشت به فروشگاه', 'url' => route('frontend.home')],
            ],
        ])

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>آخرین نوبت‌ها</h2>
            </header>
            <div class="sp-dash__list">
                @forelse($recentBookings as $booking)
                    @php [$stLabel, $stClass] = $statusMap[$booking->status] ?? [$booking->status, 'is-pending']; @endphp
                    <article class="sp-dash__row">
                        <div>
                            <strong>{{ $booking->user->name ?? 'مراجع' }} {{ $booking->user->family ?? '' }}</strong>
                            <p class="sp-dash__muted latin">{{ $booking->booking_code }}</p>
                        </div>
                        <div class="sp-dash__row-meta">
                            <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
                            <span class="sp-dash__muted">{{ optional($booking->appointment_date)->format('Y/m/d H:i') ?? '—' }}</span>
                        </div>
                    </article>
                @empty
                    <p class="sp-dash__empty-inline">نوبتی ثبت نشده است.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
