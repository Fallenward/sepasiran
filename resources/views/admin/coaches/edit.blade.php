@extends('admin.layout')
@section('title', 'ویرایش مربی')
@section('content')
@include('admin.components.page-header', [
    'title' => 'ویرایش مربی',
    'subtitle' => trim(($coach->name ?? '').' '.($coach->family ?? '')),
])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.coaches.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.coaches.update', $coach) }}">@csrf @method('PUT') @include('admin.coaches.form')</form>
</div></div>
@endsection