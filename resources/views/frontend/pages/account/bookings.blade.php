@extends('frontend.layouts.app')

@section('title', 'رزروها و درخواست‌ها — سپاسیران')

@section('content')
@php
    $statusMap = [
        'PENDING' => ['در انتظار', 'is-pending'],
        'OPEN' => ['باز', 'is-pending'],
        'QUOTED' => ['پیشنهاد شده', 'is-pending'],
        'ACCEPTED' => ['پذیرفته', 'is-active'],
        'CONFIRMED' => ['تأیید شده', 'is-active'],
        'COMPLETED' => ['انجام شده', 'is-done'],
        'CANCELLED' => ['لغو شده', 'is-danger'],
    ];
@endphp
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-nav')

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="far fa-calendar-check"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
                    <h1 class="sp-dash__title">رزروها و درخواست‌ها</h1>
                    <p class="sp-dash__lede">هم درخواست‌هایی که فرستاده‌اید و هم درخواست‌هایی که دریافت کرده‌اید.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>رزرو جدید</strong>
                    <p>خدمت تازه ثبت کنید.</p>
                </div>
                <a class="sp-btn sp-btn--primary" href="{{ route('frontend.booking.index') }}">شروع رزرو</a>
            </aside>
        </section>

        <div class="sp-dash__tabs" role="tablist">
            <a class="sp-dash__tab {{ $tab === 'mine' ? 'is-active' : '' }}"
               href="{{ route('account.bookings.index', ['tab' => 'mine']) }}">
                درخواست‌های من
                <span class="num">{{ number_format($mineCount ?? 0) }}</span>
            </a>
            @if($isProvider)
                <a class="sp-dash__tab {{ $tab === 'inbox' ? 'is-active' : '' }}"
                   href="{{ route('account.bookings.index', ['tab' => 'inbox']) }}">
                    درخواست‌های دریافتی
                    <span class="num">{{ number_format($inboxCount ?? 0) }}</span>
                </a>
            @endif
        </div>

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>{{ $tab === 'inbox' ? 'صندوق دریافتی' : 'ارسال‌شده توسط من' }}</h2>
            </header>
            <div class="sp-dash__list">
                @forelse($bookings as $booking)
                    @php [$stLabel, $stClass] = $statusMap[$booking->status] ?? [$booking->status, 'is-pending']; @endphp
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="fas fa-ticket-alt"></i></span>
                        <div>
                            <strong>
                                @if($tab === 'inbox')
                                    {{ $booking->user->name ?? 'مشتری' }} {{ $booking->user->family ?? '' }}
                                @else
                                    {{ strtoupper((string) $booking->service_type) }}
                                    @if($booking->provider)
                                        · {{ $booking->provider->name }}
                                    @endif
                                @endif
                            </strong>
                            <p class="sp-dash__muted"><span class="num">{{ $booking->booking_code }}</span></p>
                            <p class="sp-dash__muted">
                                {{ optional($booking->appointment_date ?? $booking->preferred_date)->format('Y/m/d H:i') ?? 'زمان اعلام نشده' }}
                                @if($booking->dog) · {{ $booking->dog->title }} @endif
                            </p>
                        </div>
                        <div class="sp-dash__row-meta">
                            <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
                            <span class="sp-dash__muted"><span class="num">{{ number_format((float) ($booking->total ?? $booking->price ?? 0)) }}</span> ت</span>
                        </div>
                    </article>
                @empty
                    <div class="sp-dash__empty">
                        <div class="sp-dash__empty-visual" aria-hidden="true"></div>
                        <p>{{ $tab === 'inbox' ? 'درخواست دریافتی ندارید.' : 'هنوز رزروی ارسال نکرده‌اید.' }}</p>
                    </div>
                @endforelse
            </div>
            @if(method_exists($bookings, 'links') && $bookings->hasPages())
                <div class="sp-dash__pager">{{ $bookings->links() }}</div>
            @endif
        </section>
    </div>
</div>
@endsection
