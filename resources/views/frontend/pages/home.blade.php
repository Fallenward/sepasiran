@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="container py-5">
        <div id="carouselExampleIndicators" class="mb-5 p-5 text-center text-white rounded" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/slider.png') }}"
                         class="d-block w-100 banner-img"
                         alt="بنر نمونه">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/slider.png') }}"
                         class="d-block w-100 banner-img"
                         alt="بنر نمونه">
                </div>

            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">قبلی</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">بعدی</span>
            </button>
        </div>

        <style>
            /* تنظیم عرض و ارتفاع بنر */
            .banner-img {
                width: 80%; /* عرض بنر را کوچک تر کرده‌ایم */
                height: 400px; /* طول بنر را بیشتر کرده‌ایم */
                object-fit: cover; /* تصویر به طور مناسب برش می‌خورد */
                border-radius: 15px; /* گوشه‌ها را نرم کرده‌ایم */
                margin: 0 auto; /* به صورت افقی مرکز قرار داده شده است */
            }
        </style>




        <div class="mb-5">
            <h5 class="fw-bold mb-4">اخبار</h5>
            <div class="row g-4">
                <!-- دو خبر کوچک (تعداد و ابعاد برای هم‌اندازه شدن) -->
                <div class="row g-4">
                    <div class="col-2 col-md-3">
                        <div class="card h-50">
                            <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="خبر">
                            <div class="card-body">
                                <h6 class="fw-bold">تلاش دولت آلمان برای بیکاری</h6>
                                <small class="text-muted">10:00 - 24 آذر 02</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی...</p>
                                <a href="#" class="btn btn-outline-primary btn-sm">بیشتر بخوانید</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 col-md-3">
                        <div class="card h-50">
                            <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="خبر">
                            <div class="card-body">
                                <h6 class="fw-bold">تلاش دولت آلمان برای بیکاری</h6>
                                <small class="text-muted">10:00 - 24 آذر 02</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی...</p>
                                <a href="#" class="btn btn-outline-primary btn-sm">بیشتر بخوانید</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="text-center mt-4">
                <a href="#" class="btn btn-secondary">مشاهده همه</a>
            </div>
        </div>



        <div class="mb-5">
            <h5 class="fw-bold mb-4">خدمات</h5>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <a href="{{ route('frontend.online.courses') }}" class="text-decoration-none">
                                <i class="fas fa-chalkboard-teacher fa-3x"></i>
                                <h6 class="fw-bold mt-3">دوره های آنلاین</h6>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <a href="{{ route('frontend.dog.supply.index') }}" class="text-decoration-none">
                                <i class="fas fa-dog fa-3x"></i>
                                <h6 class="fw-bold mt-3">تهیه سگ</h6>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <a href="{{ route('frontend.coach-selection.index') }}" class="text-decoration-none">
                                <i class="fas fa-user-tie fa-3x"></i>
                                <h6 class="fw-bold mt-3">انتخاب مربی</h6>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <a href="{{ route('frontend.breeders.index') }}" class="text-decoration-none">
                                <i class="fas fa-user fa-3x"></i>
                                <h6 class="fw-bold mt-3">پرورش دهنده</h6>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <a href="{{ route('frontend.clinic.insurance.index') }}" class="text-decoration-none">
                                <i class="fas fa-clinic-medical fa-3x"></i>
                                <h6 class="fw-bold mt-3">کلینیک ها و بیمه حیوانات</h6>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <a href="{{ route('frontend.stores.index') }}" class="text-decoration-none">
                                <i class="fas fa-store fa-3x"></i>
                                <h6 class="fw-bold mt-3">فروشگاه ها</h6>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-center mt-4">
                <a href="#" class="btn btn-secondary">مشاهده همه</a>
            </div>
        </div>



    <div class="mb-5 text-center">
        <h5 class="fw-bold mb-4">آمار</h5>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="counter-box bg-light p-4 rounded">
                    <h3 class="fw-bold">+53</h3>
                    <p>سگ ها</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="counter-box bg-light p-4 rounded">
                    <h3 class="fw-bold">+53</h3>
                    <p>کیلینک ها</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="counter-box bg-light p-4 rounded">
                    <h3 class="fw-bold">+53</h3>
                    <p>پرورش دهندگان</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="counter-box bg-light p-4 rounded">
                    <h3 class="fw-bold">+53</h3>
                    <p>تعداد مربی</p>
                </div>
            </div>
        </div>
    </div>



        <div class="bg-custom p-5 text-white text-center mb-5">
            <h5 class="fw-bold mb-4">پناهگاه</h5>
            <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
            <a href="{{ route('frontend.shelter.index') }}" class="btn btn-outline-light">مشاهده پناهگاه</a>
        </div>

        <style>
            .bg-custom {
                background-color: #A9C52F; /* رنگ پس‌زمینه به #A9C52F تغییر کرده است */
            }
        </style>

        <div class="mb-5"></div> <!-- اینجا فاصله اضافه شده است -->

    <div class="mb-5 text-center">
        <h5 class="fw-bold mb-4">بهترین ها</h5>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="مستر روتوالر" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="fw-bold">مستر روتوالر</h6>
                        <p>(از 10 نظر)</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">مشاهده پروفایل</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="مستر روتوالر" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="fw-bold">دامپزشکی دامون</h6>
                        <p>(از 10 نظر)</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">مشاهده پروفایل</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="مستر روتوالر" style="height: 200px; object-fit: cover;">

                    <div class="card-body">
                        <h6 class="fw-bold">بنیامین رضایی</h6>
                        <p>پرورش دهنده</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">مشاهده پروفایل</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="#" class="btn btn-secondary">مشاهده همه</a>
        </div>
    </div>


        <div class="mt-5 text-center">
            <h5 class="fw-bold mb-4">پیوند به سایت‌های دیگر</h5>
            <div class="d-flex justify-content-center gap-4">
                <a href="#" class="d-flex align-items-center custom-link-card p-4">
                    <img src="{{ asset('images/dogPicture.jpg') }}" alt="دولت" class="rounded-circle" width="50" height="50">
                    <span class="ms-3 custom-text">پایگاه اطلاع رسانی دولت</span>
                </a>
                <a href="#" class="d-flex align-items-center custom-link-card p-4">
                    <img src="{{ asset('images/dogPicture.jpg') }}" alt="ریاست جمهوری" class="rounded-circle" width="50" height="50">
                    <span class="ms-3 custom-text">پایگاه اطلاع رسانی ریاست جمهوری</span>
                </a>
                <a href="#" class="d-flex align-items-center custom-link-card p-4">
                    <img src="{{ asset('images/dogPicture.jpg') }}" alt="مقام معظم رهبری" class="rounded-circle" width="50" height="50">
                    <span class="ms-3 custom-text">پایگاه اطلاع رسانی دفتر مقام معظم رهبری</span>
                </a>
            </div>
        </div>

        <style>
            .custom-link-card {
                background-color: #f8f9fa; /* پس زمینه روشن برای مستطیل */
                border-radius: 15px; /* گوشه‌های نرم */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* سایه برای عمق بیشتر */
                transition: transform 0.3s ease, box-shadow 0.3s ease; /* انیمیشن ترنزیشن */
                font-size: 18px; /* فونت بزرگ‌تر */
            }

            .custom-link-card:hover {
                transform: translateY(-5px); /* حرکت به بالا هنگام هاور */
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* سایه بزرگ‌تر هنگام هاور */
            }

            .custom-text {
                font-size: 18px; /* اندازه فونت بزرگ‌تر */
                color: #333; /* رنگ متن مشکی */
                text-decoration: none; /* حذف خط زیر لینک */
            }

            .d-flex {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }
        </style>


    </div>


@endsection
