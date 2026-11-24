@extends('admin.layout')
@section('title', 'ویرایش نقش')
@section('content')
@include('admin.components.page-header', ['title' => 'ویرایش نقش', 'subtitle' => $role->name])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.roles.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.roles.update', $role) }}">@csrf @method('PUT')
@include('admin.roles.form')
</form>
</div></div>
@endsection