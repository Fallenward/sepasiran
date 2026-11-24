@extends('admin.layout')

@section('title', 'تیکت‌های پشتیبانی')

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-center">
    <h1 class="m-0">تیکت‌های پشتیبانی</h1>
    @if($openCount)<span class="badge badge-danger">{{ $openCount }} خوانده‌نشده</span>@endif
</div>
<div class="card">
    <div class="card-body">
        <form class="form-inline mb-3" method="get">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control mr-2" placeholder="کد / موضوع / کاربر">
            <select name="status" class="form-control mr-2">
                <option value="">همه وضعیت‌ها</option>
                @foreach(['open'=>'باز','answered'=>'پاسخ‌داده‌شده','closed'=>'بسته'] as $k=>$v)
                    <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary">فیلتر</button>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
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
                    <tr class="{{ $ticket->unread_for_admin ? 'table-warning' : '' }}">
                        <td class="num">{{ $ticket->code }}</td>
                        <td>{{ $ticket->subject }}</td>
                        <td>{{ $ticket->user?->name }}<br><small>{{ $ticket->user?->email }}</small></td>
                        <td>{{ $ticket->status }}</td>
                        <td class="num">{{ $ticket->unread_for_admin }}</td>
                        <td class="num">{{ optional($ticket->last_message_at)->timezone(config('app.timezone','Asia/Tehran'))->format('Y/m/d H:i') }}</td>
                        <td><a class="btn btn-sm btn-primary" href="{{ route('admin.support-tickets.show', $ticket) }}">چت</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">تیکتی نیست.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $tickets->links() }}
    </div>
</div>
@endsection