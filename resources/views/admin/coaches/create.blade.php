@extends('admin.layout')
@section('title', 'مربی جدید')
@section('content')
@include('admin.components.page-header', ['title' => 'ایجاد مربی', 'subtitle' => 'کاربر جدید با نقش مربی.'])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.coaches.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.coaches.store') }}">@csrf @include('admin.coaches.form')</form>
</div></div>
@endsection