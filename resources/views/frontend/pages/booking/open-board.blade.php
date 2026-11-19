@extends('layouts.app')

@section('title', 'درخواست‌های باز')

@section('content')
@php
    $labels = collect($serviceTypes)->pluck('label', 'key');
@endphp
<x-listing-hero
    title="درخواست‌های باز"
    lede="همه می‌توانند این درخواست‌ها را ببینند. ارائه‌دهندگان پیشنهاد و گفتگو می‌کنند؛ صاحب درخواست می‌پذیرد یا رد می‌کند. رزرو مستقیم فقط در داشبورد است."
    icon="cta"
    image="images/demo/hero/hero-03.jpg"
    :crumbs="[
        ['label' => 'خانه', 'route' => route('frontend.home')],
        ['label' => 'درخواست‌های باز'],
    ]"
/>

<div class="wrap" style="padding-top:28px;padding-bottom:56px">
    <div class="sp-dash__inline-form" style="margin-bottom:18px">
        <a class="sp-btn sp-btn--primary" href="{{ route('frontend.booking.index') }}">ثبت درخواست جدید</a>
        @auth
            <a class="sp-btn sp-btn--ghost" href="{{ route('account.bookings.index', ['tab' => 'inbox']) }}">داشبورد مستقیم‌ها</a>
        @else
            <a class="sp-btn sp-btn--ghost" href="{{ $loginUrl }}">ورود برای پیشنهاد / گفتگو</a>
        @endauth
    </div>

    <div class="sp-dash__tabs" role="tablist" style="margin-bottom:20px">
        <a class="sp-dash__tab {{ empty($selectedService) ? 'is-active' : '' }}" href="{{ route('frontend.booking.open') }}">همه</a>
        @foreach($serviceTypes as $row)
            <a class="sp-dash__tab {{ $selectedService === $row['key'] ? 'is-active' : '' }}"
               href="{{ route('frontend.booking.open', ['service_type' => $row['key']]) }}">{{ $row['label'] }}</a>
        @endforeach
    </div>

    @auth
        @if($mineOpen->isNotEmpty())
            <section class="sp-dash__panel" style="margin-bottom:20px">
                <header class="sp-dash__panel-head"><h2>درخواست‌های باز من</h2></header>
                <div class="sp-dash__list">
                    @foreach($mineOpen as $row)
                        <article class="sp-dash__row">
                            <span class="sp-dash__row-icon"><i class="fas fa-bullhorn"></i></span>
                            <div>
                                <strong>{{ $labels[$row->service_type] ?? strtoupper((string) $row->service_type) }} · {{ $row->status }}</strong>
                                <p class="sp-dash__muted"><span class="num">{{ $row->booking_code }}</span> · {{ $row->offers_count }} پیشنهاد · {{ $row->messages_count }} پیام</p>
                            </div>
                            <div class="sp-dash__row-meta">
                                <a class="sp-btn sp-btn--primary" style="min-height:34px;padding:6px 12px" href="{{ route('frontend.booking.open.show', $row->booking_code) }}">مدیریت / گفتگو</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    @endauth

    <section class="sp-dash__panel">
        <header class="sp-dash__panel-head"><h2>درخواست‌های فعال</h2></header>
        <div class="sp-dash__list">
            @forelse($bookings as $booking)
                <article class="sp-dash__row">
                    <span class="sp-dash__row-icon"><i class="fas fa-door-open"></i></span>
                    <div>
                        <strong>{{ $labels[$booking->service_type] ?? strtoupper((string) $booking->service_type) }}</strong>
                        <p class="sp-dash__muted">
                            {{ optional($booking->city)->name ?? 'شهر نامشخص' }}
                            @if($booking->dog) · {{ $booking->dog->title }} @endif
                            @if(optional($booking->dog)->breed) · {{ $booking->dog->breed->name }} @endif
                        </p>
                        <p class="sp-dash__muted">
                            <span class="num">{{ $booking->booking_code }}</span>
                            · {{ $booking->offers_count }} پیشنهاد
                            · {{ $booking->messages_count }} پیام
                        </p>
                    </div>
                    <div class="sp-dash__row-meta">
                        <span class="sp-dash__muted"><span class="num">{{ number_format((float) ($booking->total ?? $booking->price ?? 0)) }}</span> ت</span>
                        <a class="sp-btn sp-btn--primary" style="min-height:34px;padding:6px 12px" href="{{ route('frontend.booking.open.show', $booking->booking_code) }}">مشاهده / پیشنهاد</a>
                    </div>
                </article>
            @empty
                <div class="sp-dash__empty"><p>الان درخواست بازی نیست.</p></div>
            @endforelse
        </div>
        @if(method_exists($bookings, 'links') && $bookings->hasPages())
            <div class="sp-dash__pager">{{ $bookings->links() }}</div>
        @endif
    </section>
</div>
@endsection