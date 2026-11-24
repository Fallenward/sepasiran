@extends('admin.layout')
@section('title', 'نقش‌ها')
@section('content')
@include('admin.components.page-header', [
    'title' => 'نقش‌ها',
    'subtitle' => 'نقش‌های سیستم و مجوزهای متصل.',
    'createUrl' => route('admin.roles.create'),
    'createLabel' => 'نقش جدید',
])
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
<div class="card sp-admin-card"><div class="card-body">
<div class="table-responsive"><table class="table table-hover sp-admin-table">
<thead><tr><th>#</th><th>نام</th><th>مجوزها</th><th></th></tr></thead>
<tbody>
@forelse($roles as $role)
<tr>
<td class="num">{{ $role->id }}</td>
<td><strong>{{ $role->name }}</strong></td>
<td>
@forelse($role->permissions->take(8) as $permission)
<span class="sp-admin-status">{{ $permission->slug ?: $permission->name }}</span>
@empty
—
@endforelse
@if($role->permissions->count() > 8)
<span class="text-muted small">+{{ $role->permissions->count() - 8 }}</span>
@endif
</td>
<td class="sp-admin-actions">
<a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.roles.edit', $role) }}">ویرایش</a>
<form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('حذف این نقش؟')">@csrf @method('DELETE')
<button type="submit" class="btn btn-sm btn-outline-danger">حذف</button></form>
</td>
</tr>
@empty
<tr><td colspan="4" class="text-center text-muted py-4">نقشی ثبت نشده.</td></tr>
@endforelse
</tbody></table></div>
{{ $roles->links() }}
</div></div>
@endsection