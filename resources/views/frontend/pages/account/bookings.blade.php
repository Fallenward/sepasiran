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
    $lockedStatuses = $lockedStatuses ?? ['ACCEPTED', 'CONFIRMED', 'COMPLETED', 'CANCELLED'];
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
                    <p class="sp-dash__lede">اینجا فقط رزروهای مستقیم است. درخواست‌های باز را در <a href="{{ route('frontend.booking.open') }}">صفحه عمومی درخواست‌های باز</a> ببینید و پیشنهاد/گفتگو کنید.</p>
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

        @if(session('success'))
            <div class="sp-dash__flash">{{ session('success') }}</div>
        @endif
        @error('status')
            <div class="sp-dash__flash" style="border-color:rgba(220,80,80,.4);color:#f0b4b4">{{ $message }}</div>
        @enderror

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
                    @php
                        $statusKey = strtoupper((string) $booking->status);
                        [$stLabel, $stClass] = $statusMap[$statusKey] ?? [$booking->status, 'is-pending'];
                        $isLocked = in_array($statusKey, $lockedStatuses, true);
                        $isOpenFlow = ($booking->fulfillment_mode ?? 'direct') === 'open';
                        $providerCanDecide = $tab === 'inbox' && ! $isLocked;
                        $ownerCanCancel = $tab === 'mine' && ! $isLocked;
                    @endphp
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
                            <p class="sp-dash__muted"><span class="num">{{ $booking->booking_code }}</span>
                                · {{ $isOpenFlow ? 'درخواست باز' : 'مستقیم' }}
                            </p>
                            <p class="sp-dash__muted">
                                {{ optional($booking->appointment_date ?? $booking->preferred_date)->format('Y/m/d H:i') ?? 'زمان اعلام نشده' }}
                                @if($booking->dog) · {{ $booking->dog->title }} @endif
                            </p>

                            @if($providerCanDecide)
                                <div class="sp-dash__inline-form">
                                    <form method="POST" action="{{ route('account.bookings.status', $booking) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="tab" value="inbox">
                                        <input type="hidden" name="status" value="ACCEPTED">
                                        <button type="submit" class="sp-btn sp-btn--primary" style="min-height:34px;padding:6px 14px">پذیرش</button>
                                    </form>
                                    <form method="POST" action="{{ route('account.bookings.status', $booking) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="tab" value="inbox">
                                        <input type="hidden" name="status" value="CANCELLED">
                                        <button type="submit" class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 14px;color:var(--sp-danger)">رد</button>
                                    </form>
                                </div>
                            @elseif($ownerCanCancel)
                                <form class="sp-dash__inline-form" method="POST" action="{{ route('account.bookings.status', $booking) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="tab" value="mine">
                                    <input type="hidden" name="status" value="CANCELLED">
                                    <button type="submit" class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 14px;color:var(--sp-danger)">لغو درخواست</button>
                                </form>
                            @elseif($isLocked)
                                <p class="sp-dash__muted">تصمیم نهایی ثبت شده است.</p>
                            @endif
                        </div>
                        <div class="sp-dash__row-meta">
                            <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
                            <span class="sp-dash__muted"><span class="num">{{ number_format((float) ($booking->total ?? $booking->price ?? 0)) }}</span> ت</span>
                            <a class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px" href="{{ route('account.bookings.show', $booking) }}">گفتگو / تصمیم</a>
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