@extends('admin.layout')

@section('title', $user->name ?? 'کاربر')

@section('content')
@include('admin.components.page-header', [
    'title' => trim(($user->name ?? '').' '.($user->family ?? '')) ?: 'کاربر',
    'subtitle' => $user->email,
])

<div class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap">
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.users.index') }}">بازگشت</a>
    <a class="btn btn-primary btn-sm" href="{{ route('admin.users.edit', $user) }}">ویرایش</a>
</div>

<div class="card sp-admin-card">
    <div class="card-body">
        <dl class="sp-admin-kv">
            <dt>نام</dt><dd>{{ $user->name ?: '—' }}</dd>
            <dt>نام خانوادگی</dt><dd>{{ $user->family ?: '—' }}</dd>
            <dt>ایمیل</dt><dd>{{ $user->email ?: '—' }}</dd>
            <dt>تلفن</dt><dd class="num">{{ $user->phone ?: '—' }}</dd>
            <dt>آدرس</dt><dd>{{ $user->address ?: '—' }}</dd>
            <dt>کیف پول</dt><dd class="num">{{ number_format((int) ($user->wallet ?? 0)) }}</dd>
            <dt>ادمین</dt><dd>{{ !empty($user->is_admin) ? 'بله' : 'خیر' }}</dd>
            <dt>نقش‌ها</dt>
            <dd>
                @if(method_exists($user, 'getRoleNames') && $user->getRoleNames()->isNotEmpty())
                    {{ $user->getRoleNames()->implode('، ') }}
                @else
                    —
                @endif
            </dd>
            <dt>عضویت</dt><dd class="num">{{ optional($user->created_at)->timezone(config('app.timezone'))->format('Y/m/d H:i') }}</dd>
            <dt>بیو</dt><dd>{{ $user->bio ?: '—' }}</dd>
        </dl>
    </div>
</div>
@endsection