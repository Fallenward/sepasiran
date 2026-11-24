@extends('admin.layout')
@section('title', 'ویرایش '.$meta['label'])
@section('content')
@include('admin.components.page-header', [
    'title' => 'ویرایش '.$meta['label'],
    'subtitle' => trim(($provider->name ?? '').' '.($provider->family ?? '')),
])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route($meta['route'].'.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route($meta['route'].'.update', $provider) }}">@csrf @method('PUT') @include('admin.providers.form')</form>
</div></div>
@endsection