@php
    $dashNotifications = $dashNotifications ?? collect();
@endphp
<section class="sp-dash__panel">
    <header class="sp-dash__panel-head">
        <h2>اعلان‌ها</h2>
        <a href="{{ route('account.notifications.index') }}">مشاهده همه</a>
    </header>
    <div class="sp-dash__list">
        @forelse($dashNotifications as $note)
            <a class="sp-dash__row {{ empty($note->read_at) ? 'is-unread' : '' }}"
               href="{{ route('account.notifications.index', ['focus' => $note->id]) }}#notification-{{ $note->id }}"
               style="text-decoration:none;color:inherit">
                <span class="sp-dash__row-icon"><i class="fas fa-bell"></i></span>
                <div>
                    <strong>{{ $note->title ?: 'اعلان' }}</strong>
                    <p class="sp-dash__muted">{{ \Illuminate\Support\Str::limit($note->message, 100) }}</p>
                </div>
                <span class="sp-dash__muted"><span class="num">{{ optional($note->created_at)->format('Y/m/d') }}</span></span>
            </a>
        @empty
            <p class="sp-dash__empty-inline">اعلان جدیدی ندارید.</p>
        @endforelse
    </div>
</section>
