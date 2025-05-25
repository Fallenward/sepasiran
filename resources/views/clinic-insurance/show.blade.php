@extends('layouts.app')

@section('title', $insurance->name)

@section('content')


    <div class="container my-5">
        <div class="row g-4 align-items-center">
            <div class="col-md-4 text-center">
                <img src="{{ $insurance->logo ? asset('storage/' . $insurance->logo) : asset('images/dogPicture.jpg') }}"
                     alt="{{ $insurance->name }}"
                     class="img-fluid rounded shadow-sm" />
            </div>

            <div class="col-md-8">
                <h3 class="fw-bold">{{ $insurance->name }}</h3>

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

                <a href="{{ route('frontend.clinic.insurance.index') }}" class="btn btn-outline-secondary mt-3">
                    بازگشت به لیست بیمه‌ها
                </a>
            </div>
        </div>
    </div>
@endsection
