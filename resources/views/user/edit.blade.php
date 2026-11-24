@extends('admin.layout')
@section('title', 'ویرایش کاربر')
@section('content')
@include('admin.components.page-header', [
    'title' => 'ویرایش کاربر',
    'subtitle' => trim(($user->name ?? '').' '.($user->family ?? '')),
])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.users.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT') @include('user.form')</form>
</div></div>
@endsection