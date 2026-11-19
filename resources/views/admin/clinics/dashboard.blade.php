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
<div class="sp-dash sp-dash--clinic">
    <div class="sp-dash__inner">
                @include('frontend.partials.dashboard-nav')
@include('frontend.partials.dashboard-hero', [
            'user' => $user,
            'roleLabel' => $roleLabel ?? 'کلینیک',
            'lede' => 'نوبت‌های فعال و درآمد تکمیل‌شده کلینیک را اینجا ببینید.',
            'ctaTitle' => 'پروفایل کلینیک',
            'ctaHint' => 'آدرس، تماس و تصویر کلینیک را برای مراجعان کامل کنید.',
            'primaryAction' => ['label' => 'ویرایش پروفایل', 'url' => route('account.profile.edit')],
        ])

        @include('frontend.partials.dashboard-metrics', [
            'metrics' => [
                ['label' => 'نوبت‌های تأییدشده', 'value' => number_format($activeVisits), 'hint' => 'در حال انجام', 'icon' => 'fas fa-stethoscope'],
                ['label' => 'در صف', 'value' => number_format($pendingVisits ?? 0), 'hint' => 'منتظر تأیید', 'icon' => 'fas fa-hourglass-half'],
                ['label' => 'درآمد تکمیل‌شده', 'value' => number_format($clinicIncome) . ' ت', 'hint' => 'جمع سرویس‌های انجام‌شده', 'icon' => 'fas fa-coins'],
            ],
        ])

        
        @include('frontend.partials.dashboard-finance-chart', [
            'financeChart' => $financeChart,
            'chartTitle' => 'نمودار تراکنش‌های مالی',
            'chartId' => 'spClinicFinanceChart',
        ])


        @include('frontend.partials.dashboard-notifications', [
            'dashNotifications' => $dashNotifications ?? collect(),
        ])

@include('frontend.partials.dashboard-actions', [
            'actions' => [
                ['label' => 'درخواست‌های نوبت', 'hint' => 'صندوق رزرو کلینیک', 'url' => route('account.bookings.index', ['tab' => 'inbox']), 'icon' => 'fas fa-inbox'],
                ['label' => 'پروفایل کلینیک', 'hint' => 'آدرس و اطلاعات تماس', 'url' => route('account.profile.edit'), 'icon' => 'far fa-hospital'],
                ['label' => 'کیف پول', 'hint' => 'موجودی حساب', 'url' => route('wallet.index'), 'icon' => 'fas fa-wallet'],
                ['label' => 'پروفایل', 'hint' => 'ویرایش اطلاعات', 'url' => route('account.profile.edit'), 'icon' => 'far fa-user'],
            ],
        ])

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>درخواست‌ها و نوبت‌ها</h2>
                <a href="{{ route('account.bookings.index', ['tab' => 'inbox']) }}">مشاهده همه</a>
            </header>
            <div class="sp-dash__list">
                @forelse($recentBookings as $booking)
                    @php [$stLabel, $stClass] = $statusMap[$booking->status] ?? [$booking->status, 'is-pending']; @endphp
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="fas fa-notes-medical"></i></span>
                        <div>
                            <strong>{{ $booking->user->name ?? 'مراجع' }} {{ $booking->user->family ?? '' }}</strong>
                            <p class="sp-dash__muted">{{ $booking->booking_code }}</p>
                        </div>
                        <div class="sp-dash__row-meta">
                            <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
                            <span class="sp-dash__muted">{{ optional($booking->appointment_date)->format('Y/m/d H:i') ?? '—' }}</span>
                        </div>
                    </article>
                @empty
                    <div class="sp-dash__empty">
                        <div class="sp-dash__empty-visual" style="background-image:linear-gradient(160deg,rgba(12,18,16,.25),rgba(7,10,8,.7)),url('/images/demo/hero/hero-03.jpg')" aria-hidden="true"></div>
                        <p>نوبتی ثبت نشده است.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
