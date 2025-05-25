@extends('layouts.app')

@section('content')
    <!-- Insurance Card Section -->
    <div class="container my-5">
        <div class="row g-4 align-items-center flex-row-reverse">
            <div class="col-md-4 text-center">
                <img src="{{ $insurance->logo ? asset('storage/' . $insurance->logo) : asset('images/dogPicture.jpg') }}"
                     alt="{{ $insurance->name }}"
                     class="img-fluid rounded shadow-sm" />
            </div>

            <div class="col-md-8">
                <h3 class="fw-bold">{{ $insurance->name }}</h3>
                <div style="width: 60px; height: 4px; background-color: #0d47a1;" class="my-3 rounded"></div>

                @if($insurance->description)
                    <p class="mt-3 text-muted">{{ $insurance->description }}</p>
                @endif

                <ul class="list-unstyled mt-4">
                    @if($insurance->contact_phone)
                        <li><strong>تلفن تماس:</strong> {{ $insurance->contact_phone }}</li>
                    @endif
                    @if($insurance->contact_email)
                        <li><strong>ایمیل:</strong> {{ $insurance->contact_email }}</li>
                    @endif
                    @if($insurance->website)
                        <li><strong>وب‌سایت:</strong>
                            <a href="{{ $insurance->website }}" target="_blank">{{ $insurance->website }}</a>
                        </li>
                    @endif
                    <li><strong>وضعیت:</strong> {{ $insurance->is_active ? 'فعال' : 'غیرفعال' }}</li>
                    <li><strong>شهر:</strong> {{ $insurance->city->name ?? 'نامشخص' }}</li>
                </ul>

                <a href="{{ route('frontend.clinic.insurance.index') }}" class="btn btn-primary mt-3">
                    بازگشت به لیست بیمه‌ها
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <section class="statistics-section section-spacing">
        <div class="container-fluid px-5">
            <div class="row g-4">
                @foreach($statistics as $item)
                    <div class="col-6 col-md-6 col-lg-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <p class="card-text-counter fs-3 fw-bold text-primary">
                                    {{ $item['counter'] }}<i class="fa fa-plus ms-1"></i>
                                </p>
                                <h5 class="card-title-counter">{{ $item['title'] }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Repeat Stats Section -->
    <section class="statistics-section section-spacing">
        <div class="container-fluid px-5">
            <div class="row g-4">
                @foreach($statistics as $item)
                    <div class="col-6 col-md-6 col-lg-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <p class="card-text-counter fs-3 fw-bold text-primary">
                                    {{ $item['counter'] }}<i class="fa fa-plus ms-1"></i>
                                </p>
                                <h5 class="card-title-counter">{{ $item['title'] }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Comments Section -->
    <div class="container my-5">
        <h4 class="fw-bold mb-4">نظرات کاربران</h4>
        <ul class="list-unstyled">
            @foreach($comments as $comment)
                <li class="mb-4 d-flex align-items-start">
                    <img src="{{ $comment->user->avatar ?? asset('images/default-avatar.png') }}"
                         alt="{{ $comment->user->name ?? 'کاربر' }}"
                         class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">

                    <div>
                        <h6 class="mb-1 fw-bold">{{ $comment->user->name ?? 'کاربر' }}</h6>

                        <div class="text-warning mb-2">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa fa-star"></i>
                            @endfor
                        </div>

                        <p>{{ $comment->content ?? '' }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
