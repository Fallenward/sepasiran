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
<div class="sp-dash">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-hero', [
            'user' => $user,
            'roleLabel' => $roleLabel ?? 'مربی',
            'lede' => 'جلسات امروز، درخواست‌های باز و دسترسی سریع به کیف پول.',
            'primaryAction' => ['label' => 'ویرایش پروفایل', 'url' => route('coaches.profile.edit')],
        ])

        @include('frontend.partials.dashboard-metrics', [
            'metrics' => [
                ['label' => 'جلسات امروز', 'value' => number_format($todaysAppointments->count()), 'hint' => 'برنامه روزانه'],
                ['label' => 'درخواست‌های باز', 'value' => number_format($pendingBookings ?? 0), 'hint' => 'نیاز به پاسخ'],
                ['label' => 'دوره‌ها', 'value' => number_format($teacherCourses->count()), 'hint' => 'منتشرشده'],
            ],
        ])

        @include('frontend.partials.dashboard-actions', [
            'actions' => [
                ['label' => 'کیف پول مربی', 'hint' => 'دریافت‌ها و موجودی', 'url' => route('coaches.wallet')],
                ['label' => 'لیست مربی‌گری', 'hint' => 'نمایش عمومی', 'url' => route('coaches.listing')],
                ['label' => 'پروفایل', 'hint' => 'نام، تخصص، شهر', 'url' => route('coaches.profile.edit')],
                ['label' => 'خانه', 'hint' => 'بازگشت به فروشگاه', 'url' => route('frontend.home')],
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
                    <p class="sp-dash__empty-inline">جلسه‌ای برای امروز ثبت نشده است.</p>
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
