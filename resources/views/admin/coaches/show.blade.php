@extends('admin.layout')
@section('title', trim(($coach->name ?? '').' '.($coach->family ?? '')) ?: 'مربی')
@section('content')
@include('admin.components.page-header', [
    'title' => trim(($coach->name ?? '').' '.($coach->family ?? '')) ?: 'مربی',
    'subtitle' => $coach->email,
])
<div class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap">
<a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.coaches.index') }}">بازگشت</a>
<a class="btn btn-primary btn-sm" href="{{ route('admin.coaches.edit', $coach) }}">ویرایش</a>
</div>
<div class="card sp-admin-card"><div class="card-body"><dl class="sp-admin-kv">
<dt>نام</dt><dd>{{ $coach->name ?: '—' }}</dd>
<dt>نام خانوادگی</dt><dd>{{ $coach->family ?: '—' }}</dd>
<dt>ایمیل</dt><dd>{{ $coach->email ?: '—' }}</dd>
<dt>تلفن</dt><dd class="num">{{ $coach->phone ?: '—' }}</dd>
<dt>شهر</dt><dd>{{ optional($coach->city)->name ?: '—' }}</dd>
<dt>نقش‌ها</dt>
<dd>
@forelse($coach->roles as $role)
<span class="sp-admin-status is-open">{{ $role->name }}</span>
@empty
—
@endforelse
</dd>
<dt>عضویت</dt><dd class="num">{{ optional($coach->created_at)->timezone(config('app.timezone'))->format('Y/m/d H:i') }}</dd>
<dt>بیو</dt><dd>{{ $coach->bio ?: '—' }}</dd>
</dl></div></div>
@endsection