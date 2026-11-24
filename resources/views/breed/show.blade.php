@extends('admin.layout')
@section('title', $breed->name ?? 'نژاد')
@section('content')
@include('admin.components.page-header', ['title' => $breed->name ?: 'جزئیات نژاد', 'subtitle' => '#'.$breed->id])
<div class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap">
<a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.breeds.index') }}">بازگشت</a>
<a class="btn btn-primary btn-sm" href="{{ route('admin.breeds.edit', $breed) }}">ویرایش</a>
</div>
<div class="card sp-admin-card"><div class="card-body"><dl class="sp-admin-kv">
<dt>نام</dt><dd>{{ $breed->name ?: '—' }}</dd>
<dt>توضیحات</dt><dd>{{ $breed->description ?: '—' }}</dd>
<dt>تاریخ ثبت</dt><dd class="num">{{ optional($breed->created_at)->timezone(config('app.timezone'))->format('Y/m/d H:i') }}</dd>
</dl></div></div>
@endsection