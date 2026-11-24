@extends('admin.layout')

@section('title', 'تیکت '.$ticket->code)

@section('content')
@php $tz = config('app.timezone','Asia/Tehran'); @endphp
@include('admin.components.page-header', [
    'title' => $ticket->subject,
    'subtitle' => $ticket->code.' · '.$ticket->status,
])

<div class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap">
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.support-tickets.index') }}">بازگشت به لیست</a>
</div>

<div class="row gy-3">
    <div class="col-md-4">
        <div class="card sp-admin-card">
            <div class="card-header"><strong>کاربر</strong></div>
            <div class="card-body">
                <dl class="sp-admin-kv">
                    <dt>نام</dt><dd>{{ $ticket->user?->name ?: '—' }}</dd>
                    <dt>ایمیل</dt><dd>{{ $ticket->user?->email ?: '—' }}</dd>
                    <dt>تلفن</dt><dd class="num">{{ $ticket->user?->phone ?: '—' }}</dd>
                    <dt>وضعیت</dt><dd><span class="sp-admin-status">{{ $ticket->status }}</span></dd>
                    <dt>خوانده‌نشده</dt><dd class="num">{{ $ticket->unread_for_admin }}</dd>
                    <dt>آخرین پیام</dt><dd class="num">{{ optional($ticket->last_message_at)->timezone($tz)->format('Y/m/d H:i') ?: '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card sp-admin-card">
            <div class="card-header"><strong>گفتگو</strong></div>
            <div class="card-body" id="admin-support-thread" style="max-height:480px;overflow:auto">
                @forelse($ticket->messages as $message)
                    <div class="mb-3 {{ $message->sender_type === 'admin' ? 'text-left' : 'text-right' }}" data-id="{{ $message->id }}">
                        <div class="d-inline-block p-2 rounded {{ $message->sender_type === 'admin' ? 'sp-admin-bubble-admin' : 'sp-admin-bubble-user' }}" style="max-width:85%">
                            @if($message->body)<div>{{ $message->body }}</div>@endif
                            @if($message->attachment_url)
                                <div><a href="{{ $message->attachment_url }}" target="_blank" rel="noopener">پیوست</a></div>
                            @endif
                            <small class="d-block num" style="opacity:.8">{{ optional($message->created_at)->timezone($tz)->format('Y/m/d H:i') }} · {{ $message->sender_type === 'admin' ? 'ادمین' : 'کاربر' }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">هنوز پیامی نیست.</p>
                @endforelse
            </div>
            <div class="card-footer" style="background:#fff;border-top:1px solid var(--sp-admin-border)">
                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <form method="post" action="{{ route('admin.support-tickets.reply', $ticket) }}" enctype="multipart/form-data" style="display:grid;gap:8px">
                    @csrf
                    <textarea name="body" class="form-control" rows="3" placeholder="پاسخ ادمین…" required>{{ old('body') }}</textarea>
                    <input type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.zip">
                    <button type="submit" class="btn btn-primary" style="justify-self:start">ارسال پاسخ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const thread = document.getElementById('admin-support-thread');
  if (thread) thread.scrollTop = thread.scrollHeight;
  const ticketId = {{ (int) $ticket->id }};
  let lastId = {{ (int) ($ticket->messages->max('id') ?? 0) }};
  setInterval(async () => {
    try {
      const res = await fetch(`{{ url('/admin/support-tickets') }}/${ticketId}/messages?after_id=${lastId}`, { headers: { 'Accept':'application/json' }, credentials:'same-origin' });
      const data = await res.json();
      (data.messages || []).forEach((m) => {
        if (m.id <= lastId) return;
        lastId = m.id;
        const div = document.createElement('div');
        const isAdmin = m.sender_type === 'admin';
        div.className = 'mb-3 ' + (isAdmin ? 'text-left' : 'text-right');
        div.innerHTML = `<div class="d-inline-block p-2 rounded ${isAdmin?'sp-admin-bubble-admin':'sp-admin-bubble-user'}" style="max-width:85%">${m.body?`<div></div>`:''}${m.attachment_url?`<div><a href="${m.attachment_url}" target="_blank">پیوست</a></div>`:''}<small class="d-block num" style="opacity:.8">${m.created_at||''}</small></div>`;
        if (m.body) div.querySelector('div > div').textContent = m.body;
        thread.appendChild(div);
        thread.scrollTop = thread.scrollHeight;
      });
    } catch(e) {}
  }, 3000);
})();
</script>
@endpush