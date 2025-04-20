@extends('layouts.app')

@section('title', 'دوره‌های آنلاین')

@section('content')

    <!-- بخش عنوان و نوار مسیریابی -->
    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #5a8dee;">
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
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="دوره گله داری">
                    <div class="card-body">
                        <h6 class="fw-bold">دوره گله داری</h6>
                        <p class="text-muted small">تربیت سگ برای گله داری و کار در مزارع...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- دوره 2 -->
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="دوره تشخیص بیماری">
                    <div class="card-body">
                        <h6 class="fw-bold">دوره تشخیص بیماری و سرطان</h6>
                        <p class="text-muted small">آموزش تشخیص بیماری‌ها و سرطان در سگ‌ها...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- دوره 3 -->
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="دوره آتش نشانی">
                    <div class="card-body">
                        <h6 class="fw-bold">دوره آتش نشانی</h6>
                        <p class="text-muted small">آموزش سگ‌ها برای کمک به تیم‌های آتش‌نشانی...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- دوره 4 -->
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="دوره حفاظت و نگهبانی">
                    <div class="card-body">
                        <h6 class="fw-bold">دوره حفاظت و نگهبانی</h6>
                        <p class="text-muted small">آموزش سگ‌ها برای محافظت و نگهبانی از محل‌ها...</p>
                        <div class="d-flex justify-content-between align-items-center">
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
            <!-- دوره 1 -->
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="دوره گله داری">
                    <div class="card-body">
                        <h6 class="fw-bold">دوره گله داری</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- دوره 2 -->
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="دوره تشخیص بیماری">
                    <div class="card-body">
                        <h6 class="fw-bold">دوره تشخیص بیماری و سرطان</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- دوره 3 -->
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="دوره آتش نشانی">
                    <div class="card-body">
                        <h6 class="fw-bold">دوره آتش نشانی</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- دوره 4 -->
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="دوره حفاظت و نگهبانی">
                    <div class="card-body">
                        <h6 class="fw-bold">دوره حفاظت و نگهبانی</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش نظرات -->
    <div class="container mb-5">
        <h3 class="fw-bold mb-4">نظرات در مورد مستر روتوایلر</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="card p-3">
                    <h6 class="fw-bold">حمید حمیدی</h6>
                    <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h6 class="fw-bold">حمید حمیدی</h6>
                    <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                </div>
            </div>
        </div>
        <div class="text-center">
            <a href="#" class="btn btn-outline-primary">مشاهده بیشتر</a>
        </div>
    </div>

    <!-- بخش سوالات متداول -->
    <div class="container mb-5">
        <h3 class="fw-bold mb-4">سوالات متداول</h3>
        <div class="row">
            <div class="col-md-6">
                <h6>جلسات چقدر طول می‌کشد؟</h6>
                <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
            </div>
            <div class="col-md-6">
                <h6>آیا امکان آموزش در خانه من هست؟</h6>
                <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
            </div>
            <div class="col-md-6">
                <h6>مربیان با چه نژادهایی کار می‌کنند؟</h6>
                <p>لورم ایپسوم متن ساختگی...</p>
            </div>
            <div class="col-md-6">
                <h6>لورم ایپسوم متن ساختگی؟</h6>
                <p>لورم ایپسوم متن ساختگی...</p>
            </div>
        </div>
    </div>

@endsection
