@extends('admin.layout')
@section('title', 'ویرایش مجوز')
@section('content')
@include('admin.components.page-header', ['title' => 'ویرایش مجوز', 'subtitle' => $permission->name])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.permissions.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.permissions.update', $permission) }}">@csrf @method('PUT')
@include('admin.permissions.form')
</form>
</div></div>
@endsection