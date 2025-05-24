@extends('layouts.app')

@section('title', 'ثبت سفارش سگ')

@section('content')
<div class="container mb-5">
    <div class="row gy-4">

        <!-- Dog Info Card -->
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h5 class="fw-bold mb-4">مشخصات سگ</h5>

                <ul class="list-unstyled mb-4">
                    <li class="mb-2"><strong>نام سگ:</strong> {{ $dog->name ?? 'بدون نام' }}</li>
                    <li class="mb-2"><strong>رنگ:</strong> {{ $dog->color ?? 'نامشخص' }}</li>
                    <li class="mb-2"><strong>سن:</strong> {{ $dog->age ?? 'نامشخص' }} ماه</li>
                    <li class="mb-2"><strong>تاریخ تولد:</strong> {{ $dog->birth_date ?? 'نامشخص' }}</li>
                    <li class="mb-2"><strong>نوع پوشش:</strong> {{ $dog->coat_type ?? 'نامشخص' }}</li>
                    <li class="mb-2"><strong>رنگ چشم:</strong> {{ $dog->eye_color ?? 'نامشخص' }}</li>
                    <li class="mb-2">
                        <strong>شجره:</strong>
                        @if($dog->shajare)
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#shajareModal">مشاهده</button>
                        @else
                            ندارد
                        @endif
                    </li>
                    <li class="mb-2"><strong>مالک:</strong> {{ $dog->owner->name ?? 'مالک نامشخص' }}</li>
                    <li class="mb-2"><strong>شهر:</strong> {{ $dog->city->name ?? 'نامشخص' }}</li>
                    <li class="mb-2"><strong>تخصص:</strong> {{ $dog->specialty->name ?? 'بدون تخصص' }}</li>
                </ul>

                <!-- Features -->
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
                            <p class="mb-0 small">تحویل سریع در سراسر کشور.</p>
                        </div>
                    </div>
                    <div class="col-6 d-flex">
                        <i class="fas fa-heart fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">محبت و مراقبت</h6>
                            <p class="mb-0 small">توجه ویژه به سلامت و رفاه سگ‌ها.</p>
                        </div>
                    </div>
                    <div class="col-6 d-flex">
                        <i class="fas fa-cogs fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">خدمات پس از فروش</h6>
                            <p class="mb-0 small">پشتیبانی مداوم برای اطمینان خاطر شما.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dog Image and Actions -->
<div class="col-md-6">
    <div class="card shadow-sm p-3 h-100" style="height: 400px; max-width: 90%; margin: 0 auto; display: flex; flex-direction: column;">

        <div style="flex: 1 1 60%;">
            <img src="{{ $dog->image_url ? asset('storage/' . $dog->image_url) : asset('images/dogPicture.jpg') }}"
                 class="card-img-top w-100"
                 alt="{{ $dog->name ?? 'سگ' }}"
                 style="height: 100%; object-fit: cover; border-radius: 0.5rem;">
        </div>

        <div class="card-body text-center p-2" style="flex: 1 1 40%; display: flex; flex-direction: column; justify-content: center;">
            <h5 class="fw-bold mb-2">{{ $dog->name ?? 'بدون نام' }}</h5>
            <div class="mb-2">
                <a href="tel:{{ $dog->owner->phone ?? '#' }}" class="btn btn-outline-primary btn-sm w-100">تماس با مالک</a>
            </div>
            <div>
                <a href="#" class="btn btn-primary btn-sm w-100">ثبت سفارش</a>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<!-- Shajare Modal -->
@if($dog->shajare)
<div class="modal fade" id="shajareModal" tabindex="-1" aria-labelledby="shajareModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="shajareModalLabel">اطلاعات شجره</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
            <li class="list-group-item"><strong>پدر:</strong> {{ $dog->father_name }}</li>
            <li class="list-group-item"><strong>نژاد پدر:</strong> {{ optional($dog->fatherBreed)->name }}</li>
            <li class="list-group-item"><strong>مادر:</strong> {{ $dog->mother_name }}</li>
            <li class="list-group-item"><strong>نژاد مادر:</strong> {{ optional($dog->motherBreed)->name }}</li>
            <li class="list-group-item"><strong>مادربزرگ مادری:</strong> {{ $dog->maternal_grandmother_name }}</li>
            <li class="list-group-item"><strong>پدربزرگ مادری:</strong> {{ $dog->maternal_grandfather_name }}</li>
            <li class="list-group-item"><strong>مادربزرگ پدری:</strong> {{ $dog->paternal_grandmother_name }}</li>
            <li class="list-group-item"><strong>پدربزرگ پدری:</strong> {{ $dog->paternal_grandfather_name }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endif

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
