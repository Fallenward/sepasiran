@extends('admin.layout')
@section('title', 'بیمه‌ها')
@section('content')
@include('admin.components.page-header', ['title' => 'بیمه‌ها', 'subtitle' => 'شرکت‌ها و طرح‌های بیمه حیوانات.', 'createUrl' => route('admin.insurances.create'), 'createLabel' => 'بیمه جدید'])
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card sp-admin-card"><div class="card-body">
<div class="table-responsive"><table class="table table-hover sp-admin-table">
<thead><tr><th>#</th><th>نام</th><th>اسلاگ</th><th>تماس</th><th>فعال</th><th>ویژه</th><th></th></tr></thead>
<tbody>
@forelse($insurances as $insurance)
<tr>
<td class="num">{{ $insurance->id }}</td>
<td><strong>{{ $insurance->name ?: '—' }}</strong><div class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags((string) $insurance->description), 60) }}</div></td>
<td class="num">{{ $insurance->slug ?: '—' }}</td>
<td><div class="num">{{ $insurance->contact_phone ?: '—' }}</div><div class="text-muted small">{{ $insurance->contact_email ?: '—' }}</div></td>
<td><span class="sp-admin-status {{ $insurance->is_active ? 'is-open' : '' }}">{{ $insurance->is_active ? 'فعال' : 'غیرفعال' }}</span></td>
<td>{{ $insurance->is_featured ? 'بله' : 'خیر' }}</td>
<td class="sp-admin-actions">
<a class="btn btn-sm btn-primary" href="{{ route('admin.insurances.show', $insurance) }}">نمایش</a>
<a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.insurances.edit', $insurance) }}">ویرایش</a>
<form action="{{ route('admin.insurances.destroy', $insurance) }}" method="POST" onsubmit="return confirm('حذف شود؟')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">حذف</button></form>
</td>
</tr>
@empty
<tr><td colspan="7" class="text-center text-muted py-4">بیمه‌ای ثبت نشده.</td></tr>
@endforelse
</tbody></table></div>
{{ $insurances->withQueryString()->links() }}
</div></div>
@endsection