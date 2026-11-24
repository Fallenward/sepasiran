@extends('admin.layout')
@section('title', 'کاربر جدید')
@section('content')
@include('admin.components.page-header', ['title' => 'ایجاد کاربر', 'subtitle' => 'حساب جدید با نقش و دسترسی.'])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.users.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.users.store') }}">@csrf @include('user.form')</form>
</div></div>
@endsection