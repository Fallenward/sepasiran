@extends('admin.layout')

@section('title', 'کاربران')

@section('content')
@include('admin.components.page-header', [
    'title' => 'کاربران',
    'subtitle' => 'حساب‌ها، نقش ادمین و تماس.',
    'createUrl' => route('admin.users.create'),
    'createLabel' => 'کاربر جدید',
])

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card sp-admin-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover sp-admin-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>نام</th>
                    <th>ایمیل</th>
                    <th>تلفن</th>
                    <th>کیف پول</th>
                    <th>ادمین</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="num">{{ $user->id }}</td>
                        <td>{{ trim($user->name.' '.($user->family ?? '')) }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="num">{{ $user->phone ?: '—' }}</td>
                        <td class="num">{{ number_format((int) ($user->wallet ?? 0)) }}</td>
                        <td>
                            @if($user->is_admin)
                                <span class="sp-admin-status is-ok">بله</span>
                            @else
                                <span class="sp-admin-status">خیر</span>
                            @endif
                        </td>
                        <td class="sp-admin-actions">
                            <a class="btn btn-sm btn-primary" href="{{ route('admin.users.show', $user) }}">نمایش</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.users.edit', $user) }}">ویرایش</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">کاربری نیست.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection