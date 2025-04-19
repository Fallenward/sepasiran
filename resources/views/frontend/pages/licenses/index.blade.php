@extends('layouts.frontend')

@section('content')
    <div class="container mt-5">
        <h2>لیست لایسنس‌های شما</h2>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>شناسه لایسنس</th>
                <th>کلید لایسنس</th>
                <th>دوره</th>
                <th>تاریخ ایجاد</th>
            </tr>
            </thead>
            <tbody>
            @foreach($licenses as $license)
                <tr>
                    <td>{{ $license->license_id }}</td>
                    <td>{{ $license->license_key }}</td>
                    <td>{{ $license->course->title ?? 'نامشخص' }}</td>
                    <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($license->created_at)->format('Y/m/d H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
