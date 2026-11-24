@extends('admin.layout')
@section('title', 'ویرایش بیمه')
@section('content')
@include('admin.components.page-header', ['title' => 'ویرایش بیمه', 'subtitle' => $insurance->name ?? ''])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.insurances.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.insurances.update', $insurance) }}">@csrf @method('PUT') @include('insurance.form')</form>
</div></div>
@endsection