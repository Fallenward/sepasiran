@extends('layouts.app')

@section('template_title')
    وبلاگ
@endsection

@section('content')
    <div class="container py-4">
        <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #5a8dee;">
        <h1 class="fw-bold mb-2">وبلاگ</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('frontend.blog.index') }}" class="text-white text-decoration-none">وبلاگ</a>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- پربیننده‌ترین‌ها --}}
        <div class="mb-5">
            <h5 class="fw-bold mb-4">● پربیننده‌ترین‌ها</h5>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm rounded">
                        <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="روتوایلر">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <small>24 آذر 02</small>
                                <small>10:00 🗓️</small>
                            </div>
                            <h6 class="fw-bold">قیمت و اطلاع از سگ روتوایلر</h6>
                            <p class="text-muted small">روتوایلرها سگ‌هایی قدرتمند و وفادار هستند. برای اطلاعات دقیق در مورد قیمت‌های به‌روز و نکات مهم خرید این نژاد...</p>
                            <a href="#" class="btn btn-outline-dark btn-sm">بیشتر بخوانید</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm rounded">
                        <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="روتوایلر">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <small>24 آذر 02</small>
                                <small>10:00 🗓️</small>
                            </div>
                            <h6 class="fw-bold">نگهداری و مراقبت از سگ ژرمن شپرد</h6>
                            <p class="text-muted small">ژرمن شپردها سگ‌هایی باهوش، وفادار و پرانرژی هستند. در این مطلب با نکات مهم تربیت، تغذیه و سلامت این نژاد آشنا می‌شوید...</p>
                            <a href="#" class="btn btn-outline-dark btn-sm">بیشتر بخوانید</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm rounded">
                        <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="روتوایلر">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <small>24 آذر 02</small>
                                <small>10:00 🗓️</small>
                            </div>
                            <h6 class="fw-bold">چرا دوبرمن بهترین سگ نگهبان است؟</h6>
                            <p class="text-muted small">دوبرمن‌ها بسیار باهوش، گوش‌به‌فرمان و قدرتمند هستند. این مقاله به ویژگی‌ها و نیازهای خاص این نژاد می‌پردازد...</p>
                            <a href="#" class="btn btn-outline-dark btn-sm">بیشتر بخوانید</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="position-relative text-center my-5">
            <hr>
            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">ادامه مطالب</span>
        </div>


        {{-- آخرین مطلب --}}
        <div class="mb-5">
            <h5 class="fw-bold mb-4">آخرین مطلب</h5>
                <div class="mb-5">
                    <div class="row g-4">
                        @for ($i = 0; $i < 6; $i++)
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm rounded">
                                    <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="پست وبلاگ">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <small>24 آذر 02</small>
                                            <small>10:00 🗓️</small>
                                        </div>
                                        <h6 class="fw-bold">عنوان پست نمونه {{ $i + 1 }}</h6>
                                        <p class="text-muted small">خلاصه‌ای کوتاه از محتوای پست شماره {{ $i + 1 }} برای نمایش در لیست وبلاگ...</p>
                                        <a href="#" class="btn btn-outline-dark btn-sm">بیشتر بخوانید</a>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

            </div>
        </div>

        {{-- دکمه مشاهده بیشتر --}}
        <div class="text-center mt-4">
            <a href="#" class="btn btn-secondary">مشاهده بیشتر</a>
        </div>
    </div>
@endsection
