@extends('admin.layout')

@section('title', 'تیکت '.$ticket->code)

@section('content')
@php $tz = config('app.timezone','Asia/Tehran'); @endphp
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <p><strong>کاربر:</strong> {{ $ticket->user?->name }}</p>
                <p><strong>ایمیل:</strong> {{ $ticket->user?->email }}</p>
                <p><strong>تلفن:</strong> {{ $ticket->user?->phone ?: '—' }}</p>
                <p><strong>وضعیت:</strong> {{ $ticket->status }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body" id="admin-support-thread" style="max-height:480px;overflow:auto;background:#f8f9fa">
                @foreach($ticket->messages as $message)
                    <div class="mb-3 {{ $message->sender_type === 'admin' ? 'text-right' : 'text-left' }}" data-id="{{ $message->id }}">
                        <div class="d-inline-block p-2 rounded {{ $message->sender_type === 'admin' ? 'bg-success text-white' : 'bg-white border' }}" style="max-width:85%">
                            @if($message->body)<div>{{ $message->body }}</div>@endif
                            @if($message->attachment_url)
                                <div><a class="{{ $message->sender_type === 'admin' ? 'text-white' : '' }}" href="{{ $message->attachment_url }}" target="_blank">پیوست</a></div>
                            @endif
                            <small class="d-block num opacity-75">{{ optional($message->created_at)->timezone($tz)->format('Y/m/d H:i') }} · {{ $message->sender_type === 'admin' ? 'ادمین' : 'کاربر' }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card-footer">
                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="post" action="{{ route('admin.support-tickets.reply', $ticket) }}" enctype="multipart/form-data" class="d-flex flex-column" style="gap:8px">
                    @csrf
                    <textarea name="body" class="form-control" rows="3" placeholder="پاسخ ادمین…"></textarea>
                    <input type="file" name="attachment" class="form-control-file" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.zip">
                    <button class="btn btn-primary align-self-start">ارسال پاسخ</button>
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
      const res = await fetch(`{{ url('/admin/support-tickets') }}/${ticketId}/messages?after_id=${lastId}`, { headers: { 'Accept':'application/json' }});
      const data = await res.json();
      (data.messages || []).forEach((m) => {
        if (m.id <= lastId) return;
        lastId = m.id;
        const div = document.createElement('div');
        div.className = 'mb-3 ' + (m.sender_type === 'admin' ? 'text-right' : 'text-left');
        div.innerHTML = `<div class="d-inline-block p-2 rounded ${m.sender_type==='admin'?'bg-success text-white':'bg-white border'}" style="max-width:85%">${m.body?`<div>${m.body}</div>`:''}${m.attachment_url?`<div><a href="${m.attachment_url}" target="_blank">پیوست</a></div>`:''}<small class="d-block num">${m.created_at}</small></div>`;
        thread.appendChild(div);
        thread.scrollTop = thread.scrollHeight;
      });
    } catch(e) {}
  }, 3000);
})();
</script>
@endpush