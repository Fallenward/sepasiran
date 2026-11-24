@extends('admin.layout')
@section('title', 'مربی‌ها')
@section('content')
@include('admin.components.page-header', [
    'title' => 'مربی‌ها',
    'subtitle' => 'کاربرانی که نقش مربی دارند.',
    'createUrl' => route('admin.coaches.create'),
    'createLabel' => 'مربی جدید',
])
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<form method="get" class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap;max-width:520px">
    <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="جستجو نام، ایمیل، تلفن…">
    <button class="btn btn-outline-secondary" type="submit">جستجو</button>
</form>

<div class="card sp-admin-card"><div class="card-body">
<div class="table-responsive"><table class="table table-hover sp-admin-table">
<thead><tr><th>#</th><th>نام</th><th>ایمیل</th><th>تلفن</th><th>شهر</th><th></th></tr></thead>
<tbody>
@forelse($coaches as $coach)
@php $fullName = trim(($coach->name ?? '').' '.($coach->family ?? '')); @endphp
<tr>
<td class="num">{{ $coach->id }}</td>
<td><strong>{{ $fullName !== '' ? $fullName : '—' }}</strong></td>
<td>{{ $coach->email ?: '—' }}</td>
<td class="num">{{ $coach->phone ?: '—' }}</td>
<td>{{ optional($coach->city)->name ?: '—' }}</td>
<td class="sp-admin-actions">
<a class="btn btn-sm btn-primary" href="{{ route('admin.coaches.show', $coach) }}">نمایش</a>
<a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.coaches.edit', $coach) }}">ویرایش</a>
<form action="{{ route('admin.coaches.destroy', $coach) }}" method="POST" onsubmit="return confirm('نقش مربی برداشته شود؟')">@csrf @method('DELETE')
<button type="submit" class="btn btn-sm btn-outline-danger">حذف نقش</button></form>
</td>
</tr>
@empty
<tr><td colspan="6" class="text-center text-muted py-4">مربی‌ای ثبت نشده.</td></tr>
@endforelse
</tbody></table></div>
{{ $coaches->links() }}
</div></div>
@endsection