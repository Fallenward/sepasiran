@extends('admin.layout')
@section('title', 'نژادها')
@section('content')
@include('admin.components.page-header', ['title' => 'نژادها', 'subtitle' => 'فهرست نژادهای قابل استفاده در آگهی سگ.', 'createUrl' => route('admin.breeds.create'), 'createLabel' => 'نژاد جدید'])
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card sp-admin-card"><div class="card-body">
<div class="table-responsive"><table class="table table-hover sp-admin-table">
<thead><tr><th>#</th><th>نام</th><th>توضیحات</th><th></th></tr></thead>
<tbody>
@forelse($breeds as $breed)
<tr>
<td class="num">{{ $breed->id }}</td>
<td><strong>{{ $breed->name ?: '—' }}</strong></td>
<td>{{ \Illuminate\Support\Str::limit((string) $breed->description, 90) ?: '—' }}</td>
<td class="sp-admin-actions">
<a class="btn btn-sm btn-primary" href="{{ route('admin.breeds.show', $breed) }}">نمایش</a>
<a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.breeds.edit', $breed) }}">ویرایش</a>
<form action="{{ route('admin.breeds.destroy', $breed) }}" method="POST" onsubmit="return confirm('حذف شود؟')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">حذف</button></form>
</td>
</tr>
@empty
<tr><td colspan="4" class="text-center text-muted py-4">نژادی ثبت نشده.</td></tr>
@endforelse
</tbody></table></div>
{{ $breeds->withQueryString()->links() }}
</div></div>
@endsection