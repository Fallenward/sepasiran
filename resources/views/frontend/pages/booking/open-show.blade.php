@extends('layouts.app')

@section('title', 'Open booking')

@section('content')
@php
    $statusMap = [
        'PENDING' => ['pending', 'is-pending'],
        'OPEN' => ['open', 'is-pending'],
        'ACCEPTED' => ['accepted', 'is-active'],
        'CONFIRMED' => ['confirmed', 'is-active'],
        'COMPLETED' => ['completed', 'is-done'],
        'CANCELLED' => ['cancelled', 'is-danger'],
    ];
    $statusKey = strtoupper((string) $booking->status);
    [$stLabel, $stClass] = $statusMap[$statusKey] ?? [$booking->status, 'is-pending'];
    $code = $booking->booking_code;
    $isLocked = in_array($statusKey, ['ACCEPTED', 'CONFIRMED', 'COMPLETED', 'CANCELLED'], true);
    $serviceLabel = $serviceLabels[$booking->service_type] ?? strtoupper((string) $booking->service_type);
    $myOffer = $isProvider ? $booking->offers->firstWhere('provider_id', auth()->id()) : null;
    $fa = [
        'open_req' => 'درخواست باز',
        'summary' => 'خلاصه',
        'owner_you' => 'شما صاحب این درخواست هستید.',
        'customer' => 'مشتری',
        'dog' => 'سگ',
        'login_needed' => 'برای ارسال پیشنهاد، گفتگو، پذیرش یا رد باید وارد شوید.',
        'login' => 'ورود',
        'offers' => 'پیشنهادهای دریافتی',
        'no_msg' => 'بدون پیام',
        'accept' => 'پذیرش',
        'reject' => 'رد',
        'no_offers' => 'هنوز پیشنهادی نیامده.',
        'cancel_open' => 'لغو درخواست باز',
        'send_offer' => 'ارسال پیشنهاد',
        'current_offer' => 'پیشنهاد فعلی',
        'price' => 'قیمت',
        'message' => 'پیام',
        'hint' => 'پذیرش نهایی با صاحب درخواست است؛ شما پیشنهاد می‌فرستید و گفتگو می‌کنید.',
        'chat' => 'گفتگو',
        'refresh' => 'به‌روز',
        'no_chat' => 'هنوز پیامی نیست.',
        'send' => 'ارسال',
        'all_open' => 'همه درخواست‌های باز',
        'lede' => 'پیشنهاد بفرستید، گفتگو کنید؛ صاحب درخواست می‌پذیرد یا رد می‌کند.',
        'code' => 'کد',
    ];
@endphp
<div class="wrap" style="padding-top:28px;padding-bottom:56px">
    <header style="margin-bottom:22px">
        <p class="sp-dash__eyebrow">{{ $fa['open_req'] }}</p>
        <h1 class="sp-dash__title">{{ $serviceLabel }}</h1>
        <p class="sp-dash__lede">{{ $fa['code'] }} <span class="num">{{ $code }}</span> · {{ $fa['lede'] }}</p>
        <div class="sp-dash__inline-form" style="margin-top:14px">
            <a class="sp-btn sp-btn--ghost" href="{{ route('frontend.booking.open') }}">{{ $fa['all_open'] }}</a>
            <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
        </div>
    </header>

    <section class="sp-dash__panel">
        <header class="sp-dash__panel-head"><h2>{{ $fa['summary'] }}</h2></header>
        <div class="sp-dash__list">
            <article class="sp-dash__row">
                <span class="sp-dash__row-icon"><i class="fas fa-ticket-alt"></i></span>
                <div>
                    <strong>{{ $serviceLabel }} · {{ $fa['open_req'] }}</strong>
                    <p class="sp-dash__muted">
                        @if($isOwner)
                            {{ $fa['owner_you'] }}
                        @else
                            {{ $fa['customer'] }}: {{ optional($booking->user)->name }} {{ optional($booking->user)->family }}
                        @endif
                    </p>
                    <p class="sp-dash__muted">
                        {{ optional($booking->city)->name }}
                        @if($booking->dog) · {{ $fa['dog'] }}: {{ $booking->dog->title }} @endif
                        · <span class="num">{{ number_format((float) ($booking->total ?? $booking->price ?? 0)) }}</span> ت
                    </p>
                    @if($booking->notes)
                        <p class="sp-dash__muted">{{ $booking->notes }}</p>
                    @endif
                </div>
            </article>
        </div>
    </section>

    @guest
        <section class="sp-dash__panel">
            <p class="sp-dash__muted">{{ $fa['login_needed'] }}</p>
            <a class="sp-btn sp-btn--primary" href="{{ $loginUrl }}">{{ $fa['login'] }}</a>
        </section>
    @endguest

    @auth
        @if($isOwner)
            <section class="sp-dash__panel">
                <header class="sp-dash__panel-head"><h2>{{ $fa['offers'] }}</h2></header>
                <div class="sp-dash__list">
                    @forelse($booking->offers as $offer)
                        <article class="sp-dash__row">
                            <span class="sp-dash__row-icon"><i class="fas fa-handshake"></i></span>
                            <div>
                                <strong>{{ optional($offer->provider)->name }} {{ optional($offer->provider)->family }}</strong>
                                <p class="sp-dash__muted">{{ $offer->message ?: $fa['no_msg'] }}</p>
                                <p class="sp-dash__muted"><span class="num">{{ number_format((float) $offer->price) }}</span> ت · {{ $offer->status }}</p>
                                @if($offer->status === 'pending' && ! $isLocked)
                                    <div class="sp-dash__inline-form">
                                        <button type="button" class="sp-btn sp-btn--primary" style="min-height:34px;padding:6px 12px"
                                                data-offer-accept data-code="{{ $code }}" data-offer="{{ $offer->id }}">{{ $fa['accept'] }}</button>
                                        <button type="button" class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px;color:var(--sp-danger)"
                                                data-offer-reject data-code="{{ $code }}" data-offer="{{ $offer->id }}">{{ $fa['reject'] }}</button>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="sp-dash__empty"><p>{{ $fa['no_offers'] }}</p></div>
                    @endforelse
                </div>
                @if(! $isLocked)
                    <form class="sp-dash__inline-form" method="POST" action="{{ route('account.bookings.status', $booking) }}" style="margin-top:12px">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="tab" value="mine">
                        <input type="hidden" name="status" value="CANCELLED">
                        <button type="submit" class="sp-btn sp-btn--ghost" style="color:var(--sp-danger)">{{ $fa['cancel_open'] }}</button>
                    </form>
                @endif
            </section>
        @endif

        @if($isProvider && ! $isLocked)
            <section class="sp-dash__panel">
                <header class="sp-dash__panel-head"><h2>{{ $fa['send_offer'] }}</h2></header>
                @if($myOffer)
                    <p class="sp-dash__muted">{{ $fa['current_offer'] }}: <span class="num">{{ number_format((float) $myOffer->price) }}</span> ت ({{ $myOffer->status }})</p>
                @endif
                <form class="sp-dash__inline-form" id="spOfferForm" data-code="{{ $code }}">
                    <div class="field" style="margin:0;flex:1;min-width:140px">
                        <label for="offer_price">{{ $fa['price'] }}</label>
                        <input id="offer_price" class="num" name="price" type="number" min="0" step="1000" value="{{ $myOffer->price ?? $booking->total }}" required>
                    </div>
                    <div class="field" style="margin:0;flex:2;min-width:180px">
                        <label for="offer_message">{{ $fa['message'] }}</label>
                        <input id="offer_message" name="message" type="text" value="{{ $myOffer->message ?? '' }}">
                    </div>
                    <button class="sp-btn sp-btn--primary" type="submit">{{ $fa['send_offer'] }}</button>
                </form>
                <p class="sp-dash__muted" style="margin-top:12px">{{ $fa['hint'] }}</p>
            </section>
        @endif

        <section class="sp-dash__panel sp-booking-chat" data-booking-chat data-code="{{ $code }}">
            <header class="sp-dash__panel-head">
                <h2>{{ $fa['chat'] }}</h2>
                <button type="button" class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px" data-chat-refresh>{{ $fa['refresh'] }}</button>
            </header>
            <div class="sp-booking-chat__thread" data-chat-thread>
                @forelse($booking->messages as $message)
                    <div class="sp-booking-chat__bubble {{ (int) $message->user_id === (int) auth()->id() ? 'is-mine' : '' }}">
                        <strong>{{ optional($message->user)->name }}</strong>
                        <p>{{ $message->body }}</p>
                        <span class="num">{{ optional($message->created_at)->format('Y/m/d H:i') }}</span>
                    </div>
                @empty
                    <p class="sp-dash__muted" data-chat-empty>{{ $fa['no_chat'] }}</p>
                @endforelse
            </div>
            @if(! $isLocked)
                <form class="sp-booking-chat__composer" data-chat-form>
                    <input type="text" name="body" maxlength="2000" required>
                    <button class="sp-btn sp-btn--primary" type="submit">{{ $fa['send'] }}</button>
                </form>
            @endif
        </section>
    @endauth
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
    const accept = e.target.closest('[data-offer-accept]');
    if (accept) {
      e.preventDefault();
      try {
        await postJson(`/booking/api/${accept.dataset.code}/offers/${accept.dataset.offer}/accept`);
        window.location.reload();
      } catch (err) {
        window.showMessage?.(err?.message || 'error', 'error');
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
        window.showMessage?.(err?.message || 'error', 'error');
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
        window.showMessage?.(err?.message || 'error', 'error');
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
      thread.innerHTML = '<p class="sp-dash__muted" data-chat-empty>empty</p>';
      return;
    }
    thread.innerHTML = rows.map((m) => `
      <div class="sp-booking-chat__bubble ${m.mine ? 'is-mine' : ''}">
        <strong>${m.user?.display_name || 'user'}</strong>
        <p>${(m.body || '').replace(/[<>&]/g, (c) => ({'<':'&lt;','>':'&gt;','&':'&amp;'}[c]))}</p>
      </div>
    `).join('');
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
      window.showMessage?.(err?.message || 'error', 'error');
    }
  });
})();
</script>
@endpush