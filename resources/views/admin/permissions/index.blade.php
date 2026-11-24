@extends('admin.layout')
@section('title', 'مجوزها')
@section('content')
@include('admin.components.page-header', [
    'title' => 'مجوزها',
    'subtitle' => 'مجوزهای قابل اتصال به نقش‌ها.',
    'createUrl' => route('admin.permissions.create'),
    'createLabel' => 'مجوز جدید',
])
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card sp-admin-card"><div class="card-body">
<div class="table-responsive"><table class="table table-hover sp-admin-table">
<thead><tr><th>#</th><th>نام</th><th>اسلاگ</th><th></th></tr></thead>
<tbody>
@forelse($permissions as $permission)
<tr>
<td class="num">{{ $permission->id }}</td>
<td><strong>{{ $permission->name }}</strong></td>
<td class="num">{{ $permission->slug }}</td>
<td class="sp-admin-actions">
<a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.permissions.edit', $permission) }}">ویرایش</a>
<form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('حذف این مجوز؟')">@csrf @method('DELETE')
<button type="submit" class="btn btn-sm btn-outline-danger">حذف</button></form>
</td>
</tr>
@empty
<tr><td colspan="4" class="text-center text-muted py-4">مجوزی ثبت نشده.</td></tr>
@endforelse
</tbody></table></div>
{{ $permissions->links() }}
</div></div>
@endsection