@php
    $code = $booking->booking_code;
    $isOwner = $isOwner ?? false;
    $isProvider = $isProvider ?? false;
    $isLocked = $isLocked ?? false;
    $defaultProviderId = $isProvider
        ? (int) auth()->id()
        : (int) ($booking->provider_id ?? optional($booking->offers->first())->provider_id ?? 0);
@endphp
<section class="sp-dash__panel sp-booking-chat"
         data-booking-chat
         data-code="{{ $code }}"
         data-is-owner="{{ $isOwner ? '1' : '0' }}"
         data-provider-id="{{ $defaultProviderId }}">
    <header class="sp-dash__panel-head">
        <h2>گفتگو خصوصی</h2>
        <button type="button" class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px" data-chat-refresh>به‌روز</button>
    </header>
    <p class="sp-dash__muted" style="margin-bottom:10px">
        گفتگو خصوصی است (رزرو + ارائه‌دهنده). ارائه‌دهنده فقط بعد از ارسال پیشنهاد می‌تواند وارد شود، و صاحب رزرو باید اول پیام بدهد.
    </p>
    <p class="sp-dash__muted" data-chat-gate style="display:none;margin-bottom:10px;color:var(--sp-gold)">
        منتظر پیام اول صاحب رزرو باشید.
    </p>

    @if($isOwner)
        <div class="sp-booking-chat__threads" data-chat-threads>
            <p class="sp-dash__muted">در حال بارگذاری طرف‌های گفتگو…</p>
        </div>
    @endif

    <div class="sp-booking-chat__thread" data-chat-thread>
        @php
            $initial = collect($booking->messages ?? [])
                ->when($defaultProviderId > 0, fn ($c) => $c->where('provider_id', $defaultProviderId));
        @endphp
        @forelse($initial as $message)
            <div class="sp-booking-chat__bubble {{ (int) $message->user_id === (int) auth()->id() ? 'is-mine' : '' }}">
                <strong>{{ optional($message->user)->name }}</strong>
                <p>{{ $message->body }}</p>
                <span class="num">{{ optional($message->created_at)->format('Y/m/d H:i') }}</span>
            </div>
        @empty
            <p class="sp-dash__muted" data-chat-empty>هنوز پیامی در این گفتگو نیست.</p>
        @endforelse
    </div>
    @if(! $isLocked)
        <form class="sp-booking-chat__composer" data-chat-form>
            <input type="hidden" name="provider_id" value="{{ $defaultProviderId }}" data-chat-provider>
            <input type="text" name="body" maxlength="2000" placeholder="پیام خصوصی بنویسید..." required>
            <button class="sp-btn sp-btn--primary" type="submit">ارسال</button>
        </form>
    @endif
</section>

@once
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

  const chat = document.querySelector('[data-booking-chat]');
  if (!chat) return;
  const code = chat.dataset.code;
  const isOwner = chat.dataset.isOwner === '1';
  const thread = chat.querySelector('[data-chat-thread]');
  const form = chat.querySelector('[data-chat-form]');
  const providerInput = chat.querySelector('[data-chat-provider]');
  const threadsBox = chat.querySelector('[data-chat-threads]');
  let providerId = Number(chat.dataset.providerId || providerInput?.value || 0);

  const setProvider = (id) => {
    providerId = Number(id) || 0;
    chat.dataset.providerId = String(providerId);
    if (providerInput) providerInput.value = String(providerId);
    threadsBox?.querySelectorAll('[data-thread-provider]').forEach((btn) => {
      btn.classList.toggle('is-active', Number(btn.dataset.threadProvider) === providerId);
    });
  };

  const renderMessages = (rows) => {
    if (!rows.length) {
      thread.innerHTML = '<p class="sp-dash__muted" data-chat-empty>هنوز پیامی در این گفتگو نیست.</p>';
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

  const gate = chat.querySelector('[data-chat-gate]');
  const composer = form;

  const loadMessages = async () => {
    if (!providerId) {
      thread.innerHTML = '<p class="sp-dash__muted">یک طرف گفتگو را انتخاب کنید.</p>';
      return;
    }
    const res = await fetch(`/booking/api/${code}/messages?provider_id=${providerId}`, {
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
    });
    if (!res.ok) return;
    const data = await res.json();
    renderMessages(data.results || []);
    const canReply = data.can_reply !== false;
    if (gate) gate.style.display = (!isOwner && !canReply) ? '' : 'none';
    if (composer) composer.style.display = canReply ? '' : 'none';
  };

  const loadThreads = async () => {
    if (!threadsBox) return;
    const res = await fetch(`/booking/api/${code}/threads`, {
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
    });
    if (!res.ok) return;
    const data = await res.json();
    const rows = data.results || [];
    if (!rows.length) {
      threadsBox.innerHTML = '<p class="sp-dash__muted">هنوز طرف گفتگویی نیست. وقتی ارائه‌دهنده‌ای پیشنهاد بدهد، اینجا ظاهر می‌شود.</p>';
      return;
    }
    threadsBox.innerHTML = rows.map((t) => `
      <button type="button" class="sp-btn sp-btn--ghost sp-booking-chat__thread-btn ${Number(t.provider_id) === providerId ? 'is-active' : ''}"
              data-thread-provider="${t.provider_id}">
        ${t.display_name || 'ارائه‌دهنده'}
        <span class="num">(${t.message_count || 0})</span>
      </button>
    `).join('');
    if (!providerId && rows[0]) setProvider(rows[0].provider_id);
  };

  threadsBox?.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-thread-provider]');
    if (!btn) return;
    setProvider(btn.dataset.threadProvider);
    loadMessages();
  });

  chat.querySelector('[data-chat-refresh]')?.addEventListener('click', async () => {
    if (isOwner) await loadThreads();
    await loadMessages();
  });

  form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = form.querySelector('input[name="body"]');
    const body = (input?.value || '').trim();
    if (!body) return;
    if (!providerId) {
      window.showMessage?.('ابتدا طرف گفتگو را انتخاب کنید', 'error');
      return;
    }
    try {
      await postJson(`/booking/api/${code}/messages`, { body, provider_id: providerId });
      input.value = '';
      await loadMessages();
      if (isOwner) await loadThreads();
    } catch (err) {
      window.showMessage?.(err?.message || Object.values(err?.errors || {})?.[0]?.[0] || 'خطا', 'error');
    }
  });

  (async () => {
    if (isOwner) await loadThreads();
    await loadMessages();
  })();
})();
</script>
@endpush
@endonce