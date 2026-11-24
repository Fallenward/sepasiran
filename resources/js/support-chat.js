import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

function el(html) {
  const t = document.createElement('template');
  t.innerHTML = html.trim();
  return t.content.firstElementChild;
}

function csrf() {
  return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

export function initSupportChat() {
  const boot = document.getElementById('sp-support-root');
  if (!boot || boot.dataset.ready === '1') return;
  boot.dataset.ready = '1';

  let config = {};
  try { config = JSON.parse(boot.dataset.config || '{}'); } catch (_) { config = {}; }

  const state = {
    open: false,
    view: 'list', // list | new | chat
    tickets: [],
    activeId: null,
    messages: [],
    unread: 0,
    auth: false,
    pollTimer: null,
    echo: null,
  };

  const root = el(`
    <div class="sp-support" aria-live="polite">
      <button type="button" class="sp-support__fab" aria-label="پشتیبانی">
        <i class="fas fa-comments"></i>
        <span class="sp-support__badge" hidden>0</span>
      </button>
      <div class="sp-support__panel" hidden>
        <header class="sp-support__head">
          <button type="button" class="sp-support__back" hidden aria-label="بازگشت"><i class="fas fa-arrow-right"></i></button>
          <div>
            <strong class="sp-support__title">پشتیبانی</strong>
            <p class="sp-support__sub">گفتگوی زنده با تیم سپاسیران</p>
          </div>
          <button type="button" class="sp-support__close" aria-label="بستن"><i class="fas fa-times"></i></button>
        </header>
        <div class="sp-support__body"></div>
      </div>
    </div>
  `);

  document.body.appendChild(root);
  const fab = root.querySelector('.sp-support__fab');
  const badge = root.querySelector('.sp-support__badge');
  const panel = root.querySelector('.sp-support__panel');
  const body = root.querySelector('.sp-support__body');
  const backBtn = root.querySelector('.sp-support__back');
  const titleEl = root.querySelector('.sp-support__title');
  const subEl = root.querySelector('.sp-support__sub');

  function setBadge(n) {
    state.unread = n;
    if (n > 0) {
      badge.hidden = false;
      badge.textContent = String(n > 99 ? '99+' : n);
      fab.classList.add('has-unread');
    } else {
      badge.hidden = true;
      fab.classList.remove('has-unread');
    }
  }

  async function api(url, options = {}) {
    const opts = { credentials: 'same-origin', headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', ...(options.headers || {}) }, ...options };
    if (!(options.body instanceof FormData)) {
      opts.headers['X-CSRF-TOKEN'] = config.csrf || csrf();
    } else {
      opts.headers['X-CSRF-TOKEN'] = config.csrf || csrf();
    }
    const res = await fetch(url, opts);
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
      const msg = data.message || Object.values(data.errors || {})[0]?.[0] || 'خطا در ارتباط';
      throw new Error(msg);
    }
    return data;
  }

  function renderGuest() {
    body.innerHTML = `
      <div class="sp-support__empty">
        <p>برای ارسال تیکت وارد حساب شوید.</p>
        <a class="sp-btn sp-btn--primary" href="${config.loginUrl}">ورود</a>
      </div>`;
  }

  function renderList() {
    titleEl.textContent = 'پشتیبانی';
    subEl.textContent = 'تیکت‌های شما';
    backBtn.hidden = true;
    const rows = state.tickets.map((t) => `
      <button type="button" class="sp-support__ticket" data-id="${t.id}">
        <span>
          <strong>${escapeHtml(t.subject)}</strong>
          <small class="num">${escapeHtml(t.code)} · ${escapeHtml(t.last_message_at || '')}</small>
        </span>
        ${t.unread ? `<em class="sp-support__dot">${t.unread}</em>` : ''}
      </button>`).join('');

    body.innerHTML = `
      <div class="sp-support__toolbar">
        <button type="button" class="sp-btn sp-btn--primary sp-support__new">تیکت جدید</button>
      </div>
      <div class="sp-support__tickets">${rows || '<p class="sp-support__empty">هنوز تیکتی ندارید.</p>'}</div>`;

    body.querySelector('.sp-support__new')?.addEventListener('click', () => {
      state.view = 'new';
      render();
    });
    body.querySelectorAll('.sp-support__ticket').forEach((btn) => {
      btn.addEventListener('click', () => openTicket(Number(btn.dataset.id)));
    });
  }

  function renderNew() {
    titleEl.textContent = 'تیکت جدید';
    subEl.textContent = 'موضوع و شرح مشکل';
    backBtn.hidden = false;
    body.innerHTML = `
      <form class="sp-support__form">
        <label>موضوع<input name="subject" required maxlength="160" placeholder="مثلاً مشکل رزرو"></label>
        <label>شرح مشکل<textarea name="body" required rows="4" maxlength="5000" placeholder="مشکل را توضیح دهید"></textarea></label>
        <label>پیوست (اختیاری)<input type="file" name="attachment" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.zip"></label>
        <button type="submit" class="sp-btn sp-btn--primary">ارسال تیکت</button>
        <p class="sp-support__hint"></p>
      </form>`;
    const form = body.querySelector('form');
    const hint = body.querySelector('.sp-support__hint');
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      hint.textContent = 'در حال ارسال…';
      const fd = new FormData(form);
      try {
        const data = await api(config.ticketsUrl, { method: 'POST', body: fd });
        state.tickets = [data.ticket, ...state.tickets.filter((t) => t.id !== data.ticket.id)];
        state.activeId = data.ticket.id;
        state.messages = data.messages || [];
        state.view = 'chat';
        subscribeTicket(data.ticket.id);
        render();
      } catch (err) {
        hint.textContent = err.message;
      }
    });
  }

  function renderChat() {
    const ticket = state.tickets.find((t) => t.id === state.activeId);
    titleEl.textContent = ticket?.subject || 'گفتگو';
    subEl.textContent = ticket?.code || '';
    backBtn.hidden = false;
    const msgs = state.messages.map((m) => `
      <div class="sp-support__msg is-${m.sender_type}">
        <div class="sp-support__bubble">
          ${m.body ? `<p>${escapeHtml(m.body)}</p>` : ''}
          ${m.attachment_url ? `<a href="${m.attachment_url}" target="_blank" rel="noopener">پیوست: ${escapeHtml(m.attachment_name || 'فایل')}</a>` : ''}
          <small class="num">${escapeHtml(m.created_at || '')}</small>
        </div>
      </div>`).join('');

    body.innerHTML = `
      <div class="sp-support__thread">${msgs || '<p class="sp-support__empty">هنوز پیامی نیست.</p>'}</div>
      <form class="sp-support__composer">
        <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.zip" hidden>
        <button type="button" class="sp-support__attach" aria-label="پیوست"><i class="fas fa-paperclip"></i></button>
        <input type="text" name="body" placeholder="پیام…" maxlength="5000" autocomplete="off">
        <button type="submit" class="sp-support__send" aria-label="ارسال"><i class="fas fa-paper-plane"></i></button>
      </form>`;

    const thread = body.querySelector('.sp-support__thread');
    thread.scrollTop = thread.scrollHeight;
    const form = body.querySelector('form');
    const fileInput = form.querySelector('input[type="file"]');
    form.querySelector('.sp-support__attach').addEventListener('click', () => fileInput.click());
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const fd = new FormData(form);
      if (!fd.get('body') && !fileInput.files?.[0]) return;
      try {
        const data = await api(`${config.ticketsUrl}/${state.activeId}/messages`, { method: 'POST', body: fd });
        appendMessage(data.message);
        form.reset();
      } catch (err) {
        window.Swal?.fire({ icon: 'error', title: err.message, timer: 2500, showConfirmButton: false });
      }
    });
  }

  function appendMessage(message) {
    if (!message?.id) return;
    if (state.messages.some((m) => m.id === message.id)) return;
    state.messages.push(message);
    if (state.view === 'chat' && state.activeId === message.ticket_id) {
      renderChat();
    }
  }

  function render() {
    if (!state.auth) return renderGuest();
    if (state.view === 'new') return renderNew();
    if (state.view === 'chat') return renderChat();
    return renderList();
  }

  async function refreshTickets() {
    if (!state.auth) return;
    const data = await api(config.ticketsUrl);
    state.tickets = data.tickets || [];
    setBadge(data.unread || 0);
    if (state.view === 'list' && state.open) renderList();
  }

  async function openTicket(id) {
    state.activeId = id;
    state.view = 'chat';
    const data = await api(`${config.ticketsUrl}/${id}`);
    state.messages = data.messages || [];
    const idx = state.tickets.findIndex((t) => t.id === id);
    if (idx >= 0) state.tickets[idx] = data.ticket;
    else state.tickets.unshift(data.ticket);
    setBadge(Math.max(0, state.unread - (data.ticket?.unread || 0)));
    subscribeTicket(id);
    render();
  }

  function subscribeTicket(id) {
    if (!state.echo) return;
    state.echo.private(`support.ticket.${id}`).listen('.message.created', (payload) => {
      if (payload?.message) appendMessage(payload.message);
      if (payload?.sender_type === 'admin') {
        setBadge((state.unread || 0) + 1);
        if (!state.open) beep();
      }
    });
  }

  function beep() {
    try {
      const ctx = new (window.AudioContext || window.webkitAudioContext)();
      const o = ctx.createOscillator();
      const g = ctx.createGain();
      o.connect(g); g.connect(ctx.destination);
      o.frequency.value = 880; g.gain.value = 0.04;
      o.start(); setTimeout(() => { o.stop(); ctx.close(); }, 180);
    } catch (_) {}
  }

  function setupEcho(userId) {
    const r = config.reverb || {};
    if (!r.enabled || !r.key) return;
    window.Pusher = Pusher;
    try {
      state.echo = new Echo({
        broadcaster: 'reverb',
        key: r.key,
        wsHost: r.host || '127.0.0.1',
        wsPort: r.port || 8080,
        wssPort: r.port || 8080,
        forceTLS: (r.scheme || 'http') === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
        auth: { headers: { 'X-CSRF-TOKEN': config.csrf || csrf() } },
      });
      state.echo.private(`App.Models.User.${userId}`).listen('.message.created', (payload) => {
        if (payload?.message) appendMessage(payload.message);
        if (payload?.sender_type === 'admin') {
          setBadge((state.unread || 0) + 1);
          if (!state.open) beep();
          refreshTickets().catch(() => {});
        }
      });
    } catch (_) {
      state.echo = null;
    }
  }

  function startPoll() {
    clearInterval(state.pollTimer);
    state.pollTimer = setInterval(async () => {
      if (!state.auth) return;
      try {
        if (state.view === 'chat' && state.activeId) {
          const last = state.messages[state.messages.length - 1]?.id || 0;
          const data = await api(`${config.ticketsUrl}/${state.activeId}/messages?after_id=${last}`);
          (data.messages || []).forEach(appendMessage);
        } else {
          await refreshTickets();
        }
      } catch (_) {}
    }, 3500);
  }

  function escapeHtml(s) {
    return String(s || '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  }

  fab.addEventListener('click', () => {
    state.open = !state.open;
    panel.hidden = !state.open;
    if (state.open) render();
  });
  root.querySelector('.sp-support__close').addEventListener('click', () => {
    state.open = false;
    panel.hidden = true;
  });
  backBtn.addEventListener('click', () => {
    state.view = 'list';
    state.activeId = null;
    render();
  });

  document.addEventListener('click', (e) => {
    const opener = e.target.closest('[data-sp-support-open]');
    if (!opener) return;
    state.open = true;
    panel.hidden = false;
    render();
  });

  (async () => {
    try {
      const bootData = await api(config.bootstrapUrl);
      state.auth = !!bootData.auth;
      setBadge(bootData.unread || 0);
      if (state.auth) {
        setupEcho(bootData.user?.id || config.userId);
        await refreshTickets();
        startPoll();
      }
    } catch (_) {
      state.auth = false;
    }
  })();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initSupportChat);
} else {
  initSupportChat();
}