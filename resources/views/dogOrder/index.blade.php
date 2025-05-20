@extends('layouts.app')

@section('title', 'ثبت سفارش سگ')

@section('content')

<div class="container mb-5">
    <div class="row gy-4">

        <!-- Dog Info Card (Right Side on LTR, but Persian RTL layout makes it left visually) -->
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h5 class="fw-bold mb-4">مشخصات سگ</h5>

                <ul class="list-unstyled mb-4">
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>نام سگ:</strong> {{ $dog->name ?? 'بدون نام' }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>رنگ:</strong> {{ $dog->color ?? 'نامشخص' }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>سن:</strong> {{ $dog->age ?? 'نامشخص' }} ماه</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>تاریخ تولد:</strong> {{ $dog->birth_date ?? 'نامشخص' }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>نوع پوشش:</strong> {{ $dog->coat_type ?? 'نامشخص' }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>رنگ چشم:</strong> {{ $dog->eye_color ?? 'نامشخص' }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>شجره:</strong> {{ $dog->pedigree ? 'دارد' : 'ندارد' }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>مالک:</strong> {{ $dog->owner->name ?? 'مالک نامشخص' }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>شهر:</strong> {{ $dog->city->name ?? 'نامشخص' }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>تخصص:</strong> {{ $dog->specialty->name ?? 'بدون تخصص' }}</span>
                    </li>
                </ul>

                <!-- Feature Section -->
                <div class="row g-3">
                    <div class="col-6 d-flex">
                        <i class="fas fa-shield-alt fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">تضمین قیمت</h6>
                            <p class="mb-0 small">خرید مستقیم از پرورش‌دهندگان معتبر با تضمین مناسب‌ترین قیمت.</p>
                        </div>
                    </div>
                    <div class="col-6 d-flex">
                        <i class="fas fa-truck fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">ارسال سریع و مطمئن</h6>
                            <p class="mb-0 small">سفارش خود را از هر نقطه‌ای ثبت کنید و در کمترین زمان ممکن تحویل بگیرید.</p>
                        </div>
                    </div>

                    <div class="col-6 d-flex">
                        <i class="fas fa-heart fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">محبت و مراقبت</h6>
                            <p class="mb-0 small">توجه ویژه به سلامت و رفاه سگ‌ها با محیطی امن و مراقبتی.</p>
                        </div>
                    </div>
                    <div class="col-6 d-flex">
                        <i class="fas fa-cogs fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">خدمات پس از فروش</h6>
                            <p class="mb-0 small">پشتیبانی مداوم برای اطمینان از سلامت و راحتی سگ‌ها.</p>
                        </div>
                    </div>

                    <div class="col-6 d-flex">
                        <i class="fas fa-headset fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">پشتیبانی همیشه در دسترس</h6>
                            <p class="mb-0 small">تیم ما آماده پاسخگویی به سوالات شما در هر ساعت از شبانه‌روز است.</p>
                        </div>
                    </div>
                    <div class="col-6 d-flex">
                        <i class="fas fa-arrow-circle-right fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">مشاهده بیشتر</h6>
                            <p class="mb-0 small">برای دیدن جزئیات بیشتر و سفارش، اینجا کلیک کنید.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dog Image and Actions (Left Side visually in Persian RTL) -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <img src="{{ $dog->image_url ? asset('storage/' . $dog->image_url) : asset('images/dogPicture.jpg') }}"
                     class="card-img-top" alt="{{ $dog->name ?? 'سگ' }}">
                <div class="card-body text-center">
                    <h5 class="fw-bold">{{ $dog->name ?? 'بدون نام' }}</h5>
                    <a href="tel:{{ $dog->owner->phone ?? '#' }}" class="btn btn-outline-primary btn-sm mx-1">تماس با مالک</a>
                    <a href="#" class="btn btn-primary btn-sm mx-1">ثبت سفارش</a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- About Section -->
<div class="container mb-5">
    <div class="bg-light p-4 rounded shadow-sm">
        <h3 class="fw-bold mb-4">درباره {{ $dog->name ?? 'سگ' }}</h3>
        <p class="text-justify" style="line-height: 1.8;">
            {!! nl2br(e($dog->description ?? 'توضیحی برای این سگ موجود نیست.')) !!}
        </p>
    </div>
</div>

@endsection
