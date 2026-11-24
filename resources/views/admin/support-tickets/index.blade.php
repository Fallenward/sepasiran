@extends('admin.layout')

@section('title', 'تیکت‌های پشتیبانی')

@section('content')
@include('admin.components.page-header', [
    'title' => 'تیکت‌های پشتیبانی',
    'subtitle' => 'صف پاسخ‌گویی زنده به کاربران.',
])

<div class="mb-3">
    @if($openCount ?? 0)
        <span class="sp-admin-status is-warn">{{ $openCount }} خوانده‌نشده</span>
    @endif
</div>

<div class="card sp-admin-card">
    <div class="card-body">
        <form class="sp-admin-filters mb-3" method="get">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="کد / موضوع / کاربر">
            <select name="status" class="form-control" style="max-width:180px">
                <option value="">همه وضعیت‌ها</option>
                @foreach(['open'=>'باز','answered'=>'پاسخ‌داده‌شده','closed'=>'بسته'] as $k=>$v)
                    <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary">فیلتر</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.support-tickets.index') }}">پاک کردن</a>
        </form>

        <div class="table-responsive">
            <table class="table table-hover sp-admin-table">
                <thead>
                <tr>
                    <th>کد</th>
                    <th>موضوع</th>
                    <th>کاربر</th>
                    <th>وضعیت</th>
                    <th>خوانده‌نشده</th>
                    <th>آخرین پیام</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($tickets as $ticket)
                    @php
                        $st = (string) $ticket->status;
                        $cls = $st === 'closed' ? 'is-bad' : ($st === 'answered' ? 'is-ok' : 'is-warn');
                    @endphp
                    <tr class="{{ $ticket->unread_for_admin ? 'table-warning' : '' }}">
                        <td class="num"><strong>{{ $ticket->code }}</strong></td>
                        <td>{{ $ticket->subject }}</td>
                        <td>
                            {{ $ticket->user?->name ?: '—' }}
                            <div class="text-muted small">{{ $ticket->user?->email }}</div>
                        </td>
                        <td><span class="sp-admin-status {{ $cls }}">{{ ['open'=>'باز','answered'=>'پاسخ داده شد','closed'=>'بسته'][$st] ?? $st }}</span></td>
                        <td class="num">{{ $ticket->unread_for_admin }}</td>
                        <td class="num">{{ optional($ticket->last_message_at)->timezone(config('app.timezone','Asia/Tehran'))->format('Y/m/d H:i') ?: '—' }}</td>
                        <td><a class="btn btn-sm btn-primary" href="{{ route('admin.support-tickets.show', $ticket) }}">باز کردن چت</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">تیکتی نیست.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $tickets->links() }}
    </div>
</div>
@endsection