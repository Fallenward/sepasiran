@extends('admin.layout')
@section('title', $meta['label_plural'])
@section('content')
@include('admin.components.page-header', [
    'title' => $meta['label_plural'],
    'subtitle' => 'کاربرانی که نقش «'.$meta['role'].'» دارند.',
    'createUrl' => route($meta['route'].'.create'),
    'createLabel' => $meta['label'].' جدید',
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
@forelse($providers as $provider)
@php $fullName = trim(($provider->name ?? '').' '.($provider->family ?? '')); @endphp
<tr>
<td class="num">{{ $provider->id }}</td>
<td><strong>{{ $fullName !== '' ? $fullName : '—' }}</strong></td>
<td>{{ $provider->email ?: '—' }}</td>
<td class="num">{{ $provider->phone ?: '—' }}</td>
<td>{{ optional($provider->city)->name ?: '—' }}</td>
<td class="sp-admin-actions">
<a class="btn btn-sm btn-primary" href="{{ route($meta['route'].'.show', $provider) }}">نمایش</a>
<a class="btn btn-sm btn-outline-secondary" href="{{ route($meta['route'].'.edit', $provider) }}">ویرایش</a>
<form action="{{ route($meta['route'].'.destroy', $provider) }}" method="POST" onsubmit="return confirm('نقش {{ $meta['label'] }} برداشته شود؟')">@csrf @method('DELETE')
<button type="submit" class="btn btn-sm btn-outline-danger">حذف نقش</button></form>
</td>
</tr>
@empty
<tr><td colspan="6" class="text-center text-muted py-4">{{ $meta['empty'] }}</td></tr>
@endforelse
</tbody></table></div>
{{ $providers->links() }}
</div></div>
@endsection