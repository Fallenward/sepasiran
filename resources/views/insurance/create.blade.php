@extends('admin.layout')
@section('title', 'بیمه جدید')
@section('content')
@include('admin.components.page-header', ['title' => 'ایجاد بیمه', 'subtitle' => 'شرکت بیمه جدید را ثبت کنید.'])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.insurances.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.insurances.store') }}">@csrf @include('insurance.form')</form>
</div></div>
@endsection