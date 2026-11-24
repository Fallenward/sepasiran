@extends('admin.layout')

@section('title', 'پیام‌های تماس و پناهگاه')

@section('content')
@include('admin.components.page-header', [
    'title' => 'پیام‌های تماس / پناهگاه',
    'subtitle' => 'فرم تماس و درخواست‌های پناهگاه اینجا جمع می‌شوند.',
])

<div class="card sp-admin-card">
    <div class="card-body">
        <form method="get" class="sp-admin-filters mb-3">
            <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="جستجو در نام، ایمیل، تلفن، متن…">
            <button class="btn btn-primary">فیلتر</button>
        </form>

        <div class="table-responsive">
            <table class="table table-hover sp-admin-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>فرستنده</th>
                    <th>پیام</th>
                    <th>تاریخ</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($messages as $msg)
                    <tr>
                        <td class="num">{{ $msg->id }}</td>
                        <td>
                            <strong>{{ $msg->name }}</strong><br>
                            <small class="text-muted">{{ $msg->email }} · <span class="num">{{ $msg->phone }}</span></small>
                        </td>
                        <td style="white-space:pre-wrap;max-width:420px">{{ $msg->message }}</td>
                        <td class="num">{{ optional($msg->created_at)->timezone(config('app.timezone'))->format('Y/m/d H:i') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.support-messages.destroy', $msg) }}" onsubmit="return confirm('حذف شود؟')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">پیامی ثبت نشده.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $messages->links() }}
    </div>
</div>
@endsection