@extends('admin.layout')

@section('title', 'لیست مربیان - Sepasiran')

@section('header')
<header class="custom-header d-flex align-items-center justify-content-center">
    <h5 class="text-white m-0">مربیان ثبت‌شده</h5>
</header>
@endsection

@section('content')
<div class="container py-4 mb-5">

    <!-- Filters -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-6 col-md-2">
            <select name="breed" class="form-select">
                <option value="">نژاد</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="city" class="form-select">
                <option value="">شهر</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="specialty" class="form-select">
                <option value="">رشته تخصصی</option>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <button class="btn btn-primary w-100">نمایش نتایج</button>
        </div>
    </form>

    <!-- Coach List -->
    <h6 class="mb-2">لیست مربیان ثبت شده قبلی</h6>
    <div class="table-responsive">
        <table class="table table-bordered text-center bg-white">
            <thead class="table-light">
                <tr>
                    <th>نام مربی</th>
                    <th>شهر</th>
                    <th>نژاد</th>
                    <th>رشته تخصصی</th>
                    <th>توضیحات</th>
                    <th>اقدام</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coaches as $coach)
                    <tr>
                        <td>{{ $coach->name }}</td>
                        <td>{{ $coach->city->name }}</td>
                        <td>{{ $coach->breed->name }}</td>
                        <td>{{ $coach->specialty->name }}</td>
                        <td>{{ Str::limit($coach->description, 50) }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.coaches.destroy', $coach->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('coaches.dashboard') }}" class="btn btn-primary mt-3">افزودن مربی جدید</a>

</div>
@endsection
