@extends('admin.layout')
@section('title', 'نژاد جدید')
@section('content')
@include('admin.components.page-header', ['title' => 'ایجاد نژاد', 'subtitle' => 'نژاد جدید را به فهرست اضافه کنید.'])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.breeds.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.breeds.store') }}">@csrf @include('breed.form')</form>
</div></div>
@endsection