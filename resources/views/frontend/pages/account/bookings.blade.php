@extends('frontend.layouts.app')

@section('title', ($mode === 'inbox' ? 'درخواست‌های رزرو' : 'رزروهای من') . ' — سپاسیران')

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
                    <h1 class="sp-dash__title">{{ $mode === 'inbox' ? 'درخواست‌های رزرو' : 'رزروهای من' }}</h1>
                    <p class="sp-dash__lede">
                        {{ $mode === 'inbox'
                            ? 'درخواست‌هایی که مشتریان برای شما ارسال کرده‌اند.'
                            : 'پیگیری وضعیت رزروهای ثبت‌شده توسط شما.' }}
                    </p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>{{ $mode === 'inbox' ? 'داشبورد نقش' : 'رزرو جدید' }}</strong>
                    <p>{{ $mode === 'inbox' ? 'بازگشت به خلاصه حساب.' : 'خدمت تازه رزرو کنید.' }}</p>
                </div>
                @if($mode === 'inbox')
                    <a class="sp-btn sp-btn--ghost" href="{{ $backUrl }}">داشبورد</a>
                @else
                    <a class="sp-btn sp-btn--primary" href="{{ route('frontend.booking.index') }}">رزرو جدید</a>
                @endif
            </aside>
        </section>

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>{{ $mode === 'inbox' ? 'صندوق درخواست‌ها' : 'لیست رزروها' }}</h2>
            </header>
            <div class="sp-dash__list">
                @forelse($bookings as $booking)
                    @php [$stLabel, $stClass] = $statusMap[$booking->status] ?? [$booking->status, 'is-pending']; @endphp
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="fas fa-ticket-alt"></i></span>
                        <div>
                            <strong>
                                @if($mode === 'inbox')
                                    {{ $booking->user->name ?? 'مشتری' }} {{ $booking->user->family ?? '' }}
                                @else
                                    {{ strtoupper((string) $booking->service_type) }}
                                    @if($booking->provider)
                                        · {{ $booking->provider->name }}
                                    @endif
                                @endif
                            </strong>
                            <p class="sp-dash__muted latin">{{ $booking->booking_code }}</p>
                            <p class="sp-dash__muted">
                                {{ optional($booking->appointment_date ?? $booking->preferred_date)->format('Y/m/d H:i') ?? 'زمان اعلام نشده' }}
                                @if($booking->dog) · {{ $booking->dog->title }} @endif
                            </p>
                        </div>
                        <div class="sp-dash__row-meta">
                            <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
                            <span class="sp-dash__muted latin">{{ number_format((float) ($booking->total ?? $booking->price ?? 0)) }} ت</span>
                        </div>
                    </article>
                @empty
                    <div class="sp-dash__empty">
                        <div class="sp-dash__empty-visual" aria-hidden="true"></div>
                        <p>{{ $mode === 'inbox' ? 'درخواستی در صندوق نیست.' : 'هنوز رزروی ثبت نکرده‌اید.' }}</p>
                        @if($mode !== 'inbox')
                            <a class="sp-btn sp-btn--primary" href="{{ route('frontend.booking.index') }}">شروع رزرو</a>
                        @endif
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
