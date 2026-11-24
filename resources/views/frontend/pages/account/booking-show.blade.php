@extends('frontend.layouts.app')

@section('title', 'جزئیات رزرو — سپاسیران')

@section('content')
@php
    $statusMap = [
        'PENDING' => ['در انتظار تأیید', 'is-pending'],
        'OPEN' => ['درخواست باز', 'is-pending'],
        'QUOTED' => ['پیشنهاد شده', 'is-pending'],
        'ACCEPTED' => ['پذیرفته / در انتظار پرداخت', 'is-active'],
        'CONFIRMED' => ['تأیید نهایی', 'is-active'],
        'COMPLETED' => ['تکمیل‌شده', 'is-done'],
        'CANCELLED' => ['لغو شده', 'is-danger'],
        'REJECTED' => ['رد شده', 'is-danger'],
    ];
    $lockedStatuses = $lockedStatuses ?? ['ACCEPTED', 'CONFIRMED', 'COMPLETED', 'CANCELLED', 'REJECTED'];
    $statusKey = strtoupper((string) $booking->status);
    [$stLabel, $stClass] = $statusMap[$statusKey] ?? [$booking->status, 'is-pending'];
    $code = $booking->booking_code;
    $isOpen = ($booking->fulfillment_mode ?? 'direct') === 'open';
    $isLocked = in_array($statusKey, $lockedStatuses, true);
    $providerCanDecide = $isProvider && ! $isLocked;
    $ownerCanCancel = $isOwner && ! $isLocked;
    $myOffer = $isProvider
        ? $booking->offers->firstWhere('provider_id', auth()->id())
        : null;
@endphp
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-nav')

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="fas fa-comments"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
                    <h1 class="sp-dash__title">گفتگو و تصمیم رزرو</h1>
                    <p class="sp-dash__lede">کد <span class="num">{{ $code }}</span> · گفتگو کنید، سپس فقط بپذیرید یا رد کنید.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                @if($providerCanDecide)
                    <div>
                        <strong>تصمیم شما</strong>
                        <p>پذیرش یا رد این درخواست.</p>
                    </div>
                    <div class="sp-dash__inline-form" style="flex-wrap:wrap">
                        <button type="button" class="sp-btn sp-btn--primary" data-booking-respond="accept" data-code="{{ $code }}">پذیرش</button>
                        <button type="button" class="sp-btn sp-btn--ghost" data-booking-respond="reject" data-code="{{ $code }}" style="color:var(--sp-danger)">رد</button>
                    </div>
                @elseif($ownerCanCancel && ! $isOpen)
                    <div>
                        <strong>لغو</strong>
                        <p>درخواست را کنار بگذارید.</p>
                    </div>
                    <form method="POST" action="{{ route('account.bookings.status', $booking) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="tab" value="mine">
                        <input type="hidden" name="status" value="CANCELLED">
                        <button type="submit" class="sp-btn sp-btn--ghost" style="color:var(--sp-danger)">لغو درخواست</button>
                        {{-- Provider reject uses API respond → REJECTED; owner cancel stays CANCELLED --}}
                    </form>
                @else
                    <div>
                        <strong>بازگشت</strong>
                        <p>به لیست رزروها برگردید.</p>
                    </div>
                    <a class="sp-btn sp-btn--ghost" href="{{ $backUrl }}">رزروها</a>
                @endif
            </aside>
        </section>

        @if(session('success'))
            <div class="sp-dash__flash">{{ session('success') }}</div>
        @endif
        @error('status')
            <div class="sp-dash__flash" style="border-color:rgba(220,80,80,.4);color:#f0b4b4">{{ $message }}</div>
        @enderror

        @include('frontend.partials.booking-full-details', [
            'booking' => $booking,
            'showContact' => true,
            'hideCustomer' => false,
        ])

        @if($providerCanDecide)
            <section class="sp-dash__panel">
                <header class="sp-dash__panel-head"><h2>تصمیم</h2></header>
                <div class="sp-dash__inline-form">
                    <button type="button" class="sp-btn sp-btn--primary" data-booking-respond="accept" data-code="{{ $code }}">پذیرش درخواست</button>
                    <button type="button" class="sp-btn sp-btn--ghost" data-booking-respond="reject" data-code="{{ $code }}" style="color:var(--sp-danger)">رد درخواست</button>
                </div>
            </section>
        @elseif($isLocked)
            <p class="sp-dash__muted" style="margin: 0 0 16px">تصمیم نهایی ثبت شده است.</p>
        @endif

        @if($isOwner && $isOpen)
            <section class="sp-dash__panel">
                <header class="sp-dash__panel-head">
                    <h2>پیشنهادهای دریافتی</h2>
                </header>
                <div class="sp-dash__list">
                    @forelse($booking->offers as $offer)
                        <article class="sp-dash__row">
                            <span class="sp-dash__row-icon"><i class="fas fa-handshake"></i></span>
                            <div>
                                <strong>{{ optional($offer->provider)->name }} {{ optional($offer->provider)->family }}</strong>
                                <p class="sp-dash__muted">{{ $offer->message ?: 'بدون پیام' }}</p>
                                <p class="sp-dash__muted"><span class="num">{{ number_format((float) $offer->price) }}</span> ت · {{ $offer->status }}</p>
                                @if($offer->status === 'pending' && ! $isLocked)
                                    <div class="sp-dash__inline-form">
                                        <button type="button" class="sp-btn sp-btn--primary" style="min-height:34px;padding:6px 12px"
                                                data-offer-accept data-code="{{ $code }}" data-offer="{{ $offer->id }}">پذیرش پیشنهاد</button>
                                        <button type="button" class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px;color:var(--sp-danger)"
                                                data-offer-reject data-code="{{ $code }}" data-offer="{{ $offer->id }}">رد پیشنهاد</button>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="sp-dash__empty"><p>هنوز پیشنهادی ثبت نشده است.</p></div>
                    @endforelse
                </div>
            </section>
        @endif

        @if($isProvider && $isOpen && ! $isLocked)
            <section class="sp-dash__panel">
                <header class="sp-dash__panel-head">
                    <h2>ارسال پیشنهاد قیمت (اختیاری قبل از پذیرش)</h2>
                </header>
                @if($myOffer)
                    <p class="sp-dash__muted">پیشنهاد فعلی شما: <span class="num">{{ number_format((float) $myOffer->price) }}</span> ت ({{ $myOffer->status }})</p>
                @endif
                <form class="sp-dash__inline-form" id="spOfferForm" data-code="{{ $code }}">
                    <div class="field" style="margin:0;flex:1;min-width:140px">
                        <label for="offer_price">قیمت پیشنهادی</label>
                        <input id="offer_price" class="num" name="price" type="number" min="0" step="1000" value="{{ old('price', $myOffer->price ?? $booking->total) }}" required>
                    </div>
                    <div class="field" style="margin:0;flex:2;min-width:180px">
                        <label for="offer_message">پیام</label>
                        <input id="offer_message" name="message" type="text" value="{{ $myOffer->message ?? '' }}" placeholder="توضیح کوتاه برای مشتری">
                    </div>
                    <button class="sp-btn sp-btn--primary" type="submit">ارسال پیشنهاد</button>
                </form>
            </section>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
  const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '';
  const postJson = async (url, body = {}) => {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf(),
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      credentials: 'same-origin',
      body: JSON.stringify(body),
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw data;
    return data;
  };

  document.addEventListener('click', async (e) => {
    const respond = e.target.closest('[data-booking-respond]');
    if (respond) {
      e.preventDefault();
      try {
        await postJson(`/booking/api/${respond.dataset.code}/respond`, { action: respond.dataset.bookingRespond });
        window.location.reload();
      } catch (err) {
        window.showMessage?.(err?.message || Object.values(err?.errors || {})?.[0]?.[0] || 'خطا', 'error');
      }
      return;
    }
    const accept = e.target.closest('[data-offer-accept]');
    if (accept) {
      e.preventDefault();
      try {
        await postJson(`/booking/api/${accept.dataset.code}/offers/${accept.dataset.offer}/accept`);
        window.location.reload();
      } catch (err) {
        window.showMessage?.(err?.message || 'خطا در پذیرش', 'error');
      }
      return;
    }
    const reject = e.target.closest('[data-offer-reject]');
    if (reject) {
      e.preventDefault();
      try {
        await postJson(`/booking/api/${reject.dataset.code}/offers/${reject.dataset.offer}/reject`);
        window.location.reload();
      } catch (err) {
        window.showMessage?.(err?.message || 'خطا در رد پیشنهاد', 'error');
      }
    }
  });

  const offerForm = document.getElementById('spOfferForm');
  if (offerForm) {
    offerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const fd = new FormData(offerForm);
      try {
        await postJson(`/booking/api/${offerForm.dataset.code}/offers`, {
          price: Number(fd.get('price')),
          message: fd.get('message') || null,
        });
        window.location.reload();
      } catch (err) {
        window.showMessage?.(err?.message || 'خطا در ارسال پیشنهاد', 'error');
      }
    });
  }
})();
</script>
@endpush
