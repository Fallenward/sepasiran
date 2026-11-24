@extends('admin.layout')
@section('title', 'مجوز جدید')
@section('content')
@include('admin.components.page-header', ['title' => 'ایجاد مجوز', 'subtitle' => 'نام و اسلاگ یکتا.'])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.permissions.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.permissions.store') }}">@csrf
@include('admin.permissions.form', ['permission' => null])
</form>
</div></div>
@endsection