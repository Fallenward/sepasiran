@extends('admin.layout')
@section('title', $meta['label'].' جدید')
@section('content')
@include('admin.components.page-header', [
    'title' => 'ایجاد '.$meta['label'],
    'subtitle' => 'کاربر جدید با نقش '.$meta['label'].'.',
])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route($meta['route'].'.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route($meta['route'].'.store') }}">@csrf @include('admin.providers.form')</form>
</div></div>
@endsection