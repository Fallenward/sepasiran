@extends('admin.layout')
@section('title', $insurance->name ?? 'بیمه')
@section('content')
@include('admin.components.page-header', ['title' => $insurance->name ?: 'جزئیات بیمه', 'subtitle' => $insurance->slug ?: ('#'.$insurance->id)])
<div class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap">
<a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.insurances.index') }}">بازگشت</a>
<a class="btn btn-primary btn-sm" href="{{ route('admin.insurances.edit', $insurance) }}">ویرایش</a>
</div>
<div class="card sp-admin-card"><div class="card-body"><div class="row gy-3">
<div class="col-md-3"><img src="{{ $insurance->logoUrl() }}" alt="{{ $insurance->name }}" class="img-fluid rounded border" style="width:100%;object-fit:cover;max-height:180px"></div>
<div class="col-md-9"><dl class="sp-admin-kv">
<dt>نام</dt><dd>{{ $insurance->name ?: '—' }}</dd>
<dt>اسلاگ</dt><dd class="num">{{ $insurance->slug ?: '—' }}</dd>
<dt>تلفن</dt><dd class="num">{{ $insurance->contact_phone ?: '—' }}</dd>
<dt>ایمیل</dt><dd>{{ $insurance->contact_email ?: '—' }}</dd>
<dt>وب‌سایت</dt><dd>@if($insurance->website)<a href="{{ $insurance->website }}" target="_blank" rel="noopener">{{ $insurance->website }}</a>@else—@endif</dd>
<dt>فعال</dt><dd>{{ $insurance->is_active ? 'بله' : 'خیر' }}</dd>
<dt>ویژه</dt><dd>{{ $insurance->is_featured ? 'بله' : 'خیر' }}</dd>
<dt>توضیحات</dt><dd>{{ $insurance->description ?: '—' }}</dd>
</dl></div></div></div></div>
@endsection