@extends('admin.layout')

@section('content')
    <h1>داشبورد ادمین</h1>
    <div class="grid grid-cols-2 gap-4 mt-6">
        <div class="card">تعداد کاربران: {{ $userCount }}</div>
    </div>
@endsection
