@extends('frontend.layouts.app')

@section('title', 'جزئیات رزرو — سپاسیران')

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
    $statusKey = strtoupper((string) $booking->status);
    [$stLabel, $stClass] = $statusMap[$statusKey] ?? [$booking->status, 'is-pending'];
    $code = $booking->booking_code;
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
                    <p class="sp-dash__lede">کد <span class="num">{{ $code }}</span> · می‌توانید گفتگو کنید سپس بپذیرید یا رد کنید.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>بازگشت</strong>
                    <p>به لیست رزروها برگردید.</p>
                </div>
                <a class="sp-btn sp-btn--ghost" href="{{ $backUrl }}">رزروها</a>
            </aside>
        </section>

        @if(session('success'))
            <div class="sp-dash__flash">{{ session('success') }}</div>
        @endif

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>خلاصه درخواست</h2>
                <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
            </header>
            <div class="sp-dash__list">
                <article class="sp-dash__row">
                    <span class="sp-dash__row-icon"><i class="fas fa-ticket-alt"></i></span>
                    <div>
                        <strong>{{ strtoupper((string) $booking->service_type) }} · {{ $booking->fulfillment_mode === 'open' ? 'درخواست باز' : 'مستقیم' }}</strong>
                        <p class="sp-dash__muted">
                            @if($isOwner)
                                ارائه‌دهنده: {{ optional($booking->provider)->name ?? 'هنوز مشخص نشده' }}
                            @else
                                مشتری: {{ optional($booking->user)->name }} {{ optional($booking->user)->family }}
                            @endif
                        </p>
                        <p class="sp-dash__muted">
                            @if($booking->dog) سگ: {{ $booking->dog->title }} · @endif
                            <span class="num">{{ number_format((float) ($booking->total ?? $booking->price ?? 0)) }}</span> ت
                        </p>
                    </div>
                </article>
            </div>

            @if($isProvider && $booking->fulfillment_mode === 'direct' && $statusKey === 'PENDING')
                <div class="sp-dash__inline-form" style="margin-top:14px">
                    <button type="button" class="sp-btn sp-btn--primary" data-booking-respond="accept" data-code="{{ $code }}">پذیرش درخواست</button>
                    <button type="button" class="sp-btn sp-btn--ghost" data-booking-respond="reject" data-code="{{ $code }}" style="color:var(--sp-danger)">رد درخواست</button>
                </div>
            @endif
        </section>

        @if($isOwner && $booking->fulfillment_mode === 'open')
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
                                @if($offer->status === 'pending' && $statusKey === 'OPEN')
                                    <div class="sp-dash__inline-form">
                                        <button type="button" class="sp-btn sp-btn--primary" style="min-height:34px;padding:6px 12px"
                                                data-offer-accept data-code="{{ $code }}" data-offer="{{ $offer->id }}">پذیرش</button>
                                        <button type="button" class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px;color:var(--sp-danger)"
                                                data-offer-reject data-code="{{ $code }}" data-offer="{{ $offer->id }}">رد</button>
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

        @if($isProvider && $booking->fulfillment_mode === 'open' && $statusKey === 'OPEN')
            @php $myOffer = $booking->offers->firstWhere('provider_id', auth()->id()); @endphp
            <section class="sp-dash__panel">
                <header class="sp-dash__panel-head">
                    <h2>ارسال پیشنهاد</h2>
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

        <section class="sp-dash__panel sp-booking-chat" data-booking-chat data-code="{{ $code }}">
            <header class="sp-dash__panel-head">
                <h2>گفتگو</h2>
                <button type="button" class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px" data-chat-refresh>به‌روز</button>
            </header>
            <div class="sp-booking-chat__thread" data-chat-thread>
                @forelse($booking->messages as $message)
                    <div class="sp-booking-chat__bubble {{ (int) $message->user_id === (int) auth()->id() ? 'is-mine' : '' }}">
                        <strong>{{ optional($message->user)->name }}</strong>
                        <p>{{ $message->body }}</p>
                        <span class="num">{{ optional($message->created_at)->format('Y/m/d H:i') }}</span>
                    </div>
                @empty
                    <p class="sp-dash__muted" data-chat-empty>هنوز پیامی نیست. برای توافق ابتدا گفتگو کنید.</p>
                @endforelse
            </div>
            @if(!in_array($statusKey, ['CANCELLED', 'COMPLETED'], true))
                <form class="sp-booking-chat__composer" data-chat-form>
                    <input type="text" name="body" maxlength="2000" placeholder="پیام خود را بنویسید..." required>
                    <button class="sp-btn sp-btn--primary" type="submit">ارسال</button>
                </form>
            @endif
        </section>
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

  const chat = document.querySelector('[data-booking-chat]');
  if (!chat) return;
  const code = chat.dataset.code;
  const thread = chat.querySelector('[data-chat-thread]');
  const form = chat.querySelector('[data-chat-form]');

  const renderMessages = (rows) => {
    if (!rows.length) {
      thread.innerHTML = '<p class="sp-dash__muted" data-chat-empty>هنوز پیامی نیست. برای توافق ابتدا گفتگو کنید.</p>';
      return;
    }
    thread.innerHTML = rows.map((m) => `
      <div class="sp-booking-chat__bubble ${m.mine ? 'is-mine' : ''}">
        <strong>${m.user?.display_name || 'کاربر'}</strong>
        <p>${(m.body || '').replace(/[<>&]/g, (c) => ({'<':'&lt;','>':'&gt;','&':'&amp;'}[c]))}</p>
        <span class="num">${(m.created_at || '').replace('T', ' ').slice(0, 16)}</span>
      </div>
    `).join('');
    thread.scrollTop = thread.scrollHeight;
  };

  const loadMessages = async () => {
    const res = await fetch(`/booking/api/${code}/messages`, {
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
    });
    if (!res.ok) return;
    const data = await res.json();
    renderMessages(data.results || []);
  };

  chat.querySelector('[data-chat-refresh]')?.addEventListener('click', loadMessages);

  form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = form.querySelector('input[name="body"]');
    const body = (input?.value || '').trim();
    if (!body) return;
    try {
      await postJson(`/booking/api/${code}/messages`, { body });
      input.value = '';
      await loadMessages();
    } catch (err) {
      window.showMessage?.(err?.message || 'ارسال پیام ناموفق بود', 'error');
    }
  });
})();
</script>
@endpush