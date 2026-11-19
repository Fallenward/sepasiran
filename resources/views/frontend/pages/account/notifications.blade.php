@extends('frontend.layouts.app')

@section('title', 'اعلان‌ها — سپاسیران')

@section('content')
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-nav')

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="fas fa-bell"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
                    <h1 class="sp-dash__title">اعلان‌ها</h1>
                    <p class="sp-dash__lede">پیام‌ها و هشدارهای مرتبط با حساب شما.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>عملیات</strong>
                    <p>اعلان‌های خوانده‌نشده را یکجا پاک کنید.</p>
                </div>
                <button type="button"
                        class="sp-btn sp-btn--primary"
                        data-sp-notify-readall
                        data-url="{{ route('account.notifications.read-all') }}">
                    خواندن همه
                </button>
            </aside>
        </section>

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>لیست اعلان‌ها</h2>
                <a href="{{ $backUrl }}">داشبورد</a>
            </header>
            <div class="sp-dash__list" data-sp-notify-page-list>
                @forelse($notifications as $note)
                    <article id="notification-{{ $note->id }}"
                             class="sp-dash__row {{ $note->read_at ? '' : 'is-unread' }}"
                             data-notification-id="{{ $note->id }}">
                        <span class="sp-dash__row-icon"><i class="fas fa-bell"></i></span>
                        <div>
                            <strong>{{ $note->title ?: 'اعلان' }}</strong>
                            <p class="sp-dash__muted">{{ $note->message }}</p>
                            <p class="sp-dash__muted"><span class="num">{{ optional($note->created_at)->format('Y/m/d H:i') }}</span></p>
                        </div>
                        <div class="sp-dash__row-meta">
                            @if(!$note->read_at)
                                <button type="button"
                                        class="sp-btn sp-btn--ghost"
                                        style="min-height:36px;padding:6px 12px"
                                        data-sp-notify-mark
                                        data-url="{{ route('account.notifications.read', $note) }}">
                                    خواندم
                                </button>
                            @else
                                <span class="sp-dash__chip is-done">خوانده‌شده</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="sp-dash__empty">
                        <p>اعلانی وجود ندارد.</p>
                    </div>
                @endforelse
            </div>
            @if(method_exists($notifications, 'links') && $notifications->hasPages())
                <div class="sp-dash__pager">{{ $notifications->links() }}</div>
            @endif
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
  const focusId = @json($focusId ?? 0);
  if (!focusId) return;
  const el = document.getElementById('notification-' + focusId);
  if (!el) return;
  el.classList.add('is-focus');
  el.scrollIntoView({ behavior: 'smooth', block: 'center' });
})();
</script>
@endpush
