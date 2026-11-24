@extends('admin.layout')
@section('title', 'ویرایش نژاد')
@section('content')
@include('admin.components.page-header', ['title' => 'ویرایش نژاد', 'subtitle' => $breed->name ?? ''])
<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.breeds.index') }}">بازگشت</a>
<div class="card sp-admin-card"><div class="card-body">
<form method="POST" action="{{ route('admin.breeds.update', $breed) }}">@csrf @method('PUT') @include('breed.form')</form>
</div></div>
@endsection