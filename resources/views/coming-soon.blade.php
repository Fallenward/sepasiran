@extends('admin.layout')

@section('title', 'بزودی...')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="text-center">
            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076505.png" alt="Coming Soon" width="140" class="mb-4">

            <h2 class="fw-bold text-primary">هنوز آماده نشده 😅</h2>
            <p class="text-muted mt-2">
                این صفحه هنوز کامل نشده، ولی نگران نباش 😉<br>
                داریم روش کار می‌کنیم تا خیلی زود بیاد!
            </p>

            <a href="{{ route('admin.dashboard.index') }}" class="btn btn-outline-primary mt-4">
                برگرد به داشبورد ✨
            </a>
        </div>
    </div>
@endsection
