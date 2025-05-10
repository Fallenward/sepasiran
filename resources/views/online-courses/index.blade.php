@extends('layouts.app')

@section('title', 'دوره‌های آنلاین')

@section('content')

    <!-- بخش عنوان و نوار مسیریابی -->
    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #0D47A1;">
        <h1 class="fw-bold mb-2">دوره‌های آنلاین</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.online.courses') }}" class="text-white text-decoration-none">دوره‌های آنلاین</a>
                </li>
            </ol>
        </nav>
    </div>

    <!-- بخش دوره های آنلاین -->
    <div class="container mb-5">
        <h3 class="fw-bold mb-4">دوره های آنلاین</h3>
        <div class="row g-4">
            <!-- دوره 1 -->
            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="دوره گله داری">
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold">دوره گله داری</h6>
                        <p class="text-muted small mb-3">تربیت سگ برای گله داری و کار در مزارع...</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ... repeat for دوره 2,3,4 with same col classes ... -->
            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="دوره تشخیص بیماری">
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold">دوره تشخیص بیماری و سرطان</h6>
                        <p class="text-muted small mb-3">آموزش تشخیص بیماری‌ها و سرطان در سگ‌ها...</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- دوره 3 -->
            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="دوره آتش نشانی">
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold">دوره آتش نشانی</h6>
                        <p class="text-muted small mb-3">آموزش سگ‌ها برای کمک به تیم‌های آتش‌نشانی...</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- دوره 4 -->
            <div class="col-6 col-md-3">
                <div class="card h-100">
                    <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="دوره حفاظت و نگهبانی">
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold">دوره حفاظت و نگهبانی</h6>
                        <p class="text-muted small mb-3">آموزش سگ‌ها برای محافظت و نگهبانی از محل‌ها...</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش دوره های محبوب -->
    <div class="container mb-5">
        <h3 class="fw-bold mb-4">دوره های محبوب</h3>
        <div class="row g-4">
            <!-- Four cards, same as above but without description -->
            @foreach(range(1,4) as $i)
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="محبوب">
                        <div class="card-body d-flex flex-column">
                            <h6 class="fw-bold">دوره محبوب {{ $i }}</h6>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span>تومان 500,000</span>
                                <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

<!-- بخش نظرات -->
<div class="container mb-5">
    <h3 class="fw-bold mb-4">نظرات در مورد مستر روتوایلر</h3>
    <div class="row g-4">
        @foreach(range(1,2) as $i)
            <div class="col-12">
                <div class="card p-3 h-100">
                    <h6 class="fw-bold">حمید حمیدی</h6>
                    <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center mt-4">
        <a href="#" class="btn btn-outline-primary">مشاهده بیشتر</a>
    </div>
</div>


    <!-- اینجا: سوالات متداول، زیر همه بخش‌های بالا -->
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <h3 class="fw-bold mb-4 text-center">سوالات متداول</h3>
                <div class="row g-4">
                    <div class="col-12">
                        <h6>جلسات چقدر طول می‌کشد؟</h6>
                        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
                    </div>
                    <div class="col-12">
                        <h6>آیا امکان آموزش در خانه من هست؟</h6>
                        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
                    </div>
                    <div class="col-12">
                        <h6>مربیان با چه نژادهایی کار می‌کنند؟</h6>
                        <p>لورم ایپسوم متن ساختگی...</p>
                    </div>
                    <div class="col-12">
                        <h6>لورم ایپسوم متن ساختگی؟</h6>
                        <p>لورم ایپسوم متن ساختگی...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
