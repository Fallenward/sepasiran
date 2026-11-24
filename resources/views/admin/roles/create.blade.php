@extends('admin.layout')
@section('title', 'نقش جدید')
@section('content')
@include('admin.components.page-header', ['title' => 'ایجاد نقش', 'subtitle' => 'نام نقش و مجوزهای مرتبط.'])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.roles.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.roles.store') }}">@csrf
@include('admin.roles.form', ['role' => null, 'rolePermissions' => []])
</form>
</div></div>
@endsection