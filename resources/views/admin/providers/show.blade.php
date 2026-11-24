@extends('admin.layout')
@section('title', trim(($provider->name ?? '').' '.($provider->family ?? '')) ?: $meta['label'])
@section('content')
@include('admin.components.page-header', [
    'title' => trim(($provider->name ?? '').' '.($provider->family ?? '')) ?: $meta['label'],
    'subtitle' => $provider->email,
])
<div class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap">
<a class="btn btn-outline-secondary btn-sm" href="{{ route($meta['route'].'.index') }}">بازگشت</a>
<a class="btn btn-primary btn-sm" href="{{ route($meta['route'].'.edit', $provider) }}">ویرایش</a>
</div>
<div class="card sp-admin-card"><div class="card-body"><dl class="sp-admin-kv">
<dt>نام</dt><dd>{{ $provider->name ?: '—' }}</dd>
<dt>نام خانوادگی</dt><dd>{{ $provider->family ?: '—' }}</dd>
<dt>ایمیل</dt><dd>{{ $provider->email ?: '—' }}</dd>
<dt>تلفن</dt><dd class="num">{{ $provider->phone ?: '—' }}</dd>
<dt>شهر</dt><dd>{{ optional($provider->city)->name ?: '—' }}</dd>
<dt>نقش‌ها</dt>
<dd>
@forelse($provider->roles as $role)
<span class="sp-admin-status is-open">{{ $role->name }}</span>
@empty
—
@endforelse
</dd>
<dt>عضویت</dt><dd class="num">{{ optional($provider->created_at)->timezone(config('app.timezone'))->format('Y/m/d H:i') }}</dd>
<dt>بیو</dt><dd>{{ $provider->bio ?: '—' }}</dd>
</dl></div></div>
@endsection