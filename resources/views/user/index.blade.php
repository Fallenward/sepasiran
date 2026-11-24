@extends('admin.layout')
@section('title', 'کاربران')
@section('content')
@include('admin.components.page-header', [
    'title' => 'کاربران',
    'subtitle' => 'مدیریت حساب‌ها، نقش‌ها و دسترسی ادمین.',
    'createUrl' => route('admin.users.create'),
    'createLabel' => 'کاربر جدید',
])
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<form method="get" class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap;max-width:520px">
    <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="جستجو نام، ایمیل، تلفن…">
    <button class="btn btn-outline-secondary" type="submit">جستجو</button>
</form>

<div class="card sp-admin-card"><div class="card-body">
<div class="table-responsive"><table class="table table-hover sp-admin-table">
<thead><tr><th>#</th><th>نام</th><th>ایمیل</th><th>تلفن</th><th>نقش‌ها</th><th>کیف پول</th><th>ادمین</th><th></th></tr></thead>
<tbody>
@forelse($users as $user)
@php $fullName = trim(($user->name ?? '').' '.($user->family ?? '')); @endphp
<tr>
<td class="num">{{ $user->id }}</td>
<td><strong>{{ $fullName !== '' ? $fullName : '—' }}</strong></td>
<td>{{ $user->email ?: '—' }}</td>
<td class="num">{{ $user->phone ?: '—' }}</td>
<td>
@forelse($user->roles as $role)
<span class="sp-admin-status {{ $loop->first ? 'is-open' : '' }}">{{ $role->name }}</span>
@empty
—
@endforelse
</td>
<td class="num">{{ number_format((int) ($user->wallet ?? 0)) }}</td>
<td>@if($user->is_admin)<span class="sp-admin-status is-ok">بله</span>@else<span class="sp-admin-status">خیر</span>@endif</td>
<td class="sp-admin-actions">
<a class="btn btn-sm btn-primary" href="{{ route('admin.users.show', $user) }}">نمایش</a>
<a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.users.edit', $user) }}">ویرایش</a>
<form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('حذف این کاربر؟')">@csrf @method('DELETE')
<button type="submit" class="btn btn-sm btn-outline-danger">حذف</button></form>
</td>
</tr>
@empty
<tr><td colspan="8" class="text-center text-muted py-4">کاربری یافت نشد.</td></tr>
@endforelse
</tbody></table></div>
{{ $users->links() }}
</div></div>
@endsection