@extends('layouts.app')

@section('title', 'ثبت سفارش سگ')

@section('content')

    <!-- بخش مسیر صفحه -->
    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #5a8dee;">
        <h1 class="fw-bold mb-2">ثبت سفارش</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.dog.supply.index') }}" class="text-white text-decoration-none">تهیه سگ</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('frontend.dog.order') }}" class="text-white text-decoration-none">ثبت سفارش</a>
                </li>
            </ol>
        </nav>
    </div>

    <!-- بخش اصلی ثبت سفارش -->
    <div class="container mb-5">
        <div class="row">
            <!-- بخش اطلاعات سگ (سمت چپ) -->
            <div class="col-md-6">
                <div class="card">
                    <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">ژرمن شپرد</h5>
                        <a href="#" class="btn btn-outline-primary btn-sm">تماس</a>
                        <a href="#" class="btn btn-primary btn-sm">ثبت سفارش</a>
                    </div>
                </div>
            </div>

            <!-- بخش اطلاعات سگ (سمت راست) -->
            <div class="col-md-6">
                <div class="p-4 bg-light rounded shadow-sm">
                    <h5 class="fw-bold mb-3 text-center">مشخصات سگ</h5>
                    <ul class="list-unstyled">
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <span><strong>رنگ:</strong> مشکی</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <span><strong>سن:</strong> 6 ماه</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <span><strong>تاریخ تولد:</strong> 1403</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <span><strong>نوع پوشش:</strong> چرمی</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <span><strong>رنگ چشم:</strong> مشکی</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <span><strong>شجره:</strong> دارد</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- بخش ویژگی‌های سگ -->
        <div class="row mt-5">
            <!-- ردیف اول (تضمین قیمت و ارسال سریع) -->
            <div class="col-md-6">
                <div class="d-flex mb-4">
                    <i class="fas fa-shield-alt fa-2x me-3"></i> <!-- آیکون برای خرید از پرورش‌دهندگان -->
                    <div>
                        <h6 class="fw-bold">تضمین قیمت</h6>
                        <p>خرید مستقیم از پرورش‌دهندگان معتبر با تضمین مناسب‌ترین قیمت.</p>
                    </div>
                </div>
            </div>

            <!-- ردیف اول (ارسال سریع) -->
            <div class="col-md-6">
                <div class="d-flex mb-4">
                    <i class="fas fa-truck fa-2x me-3"></i> <!-- آیکون برای ارسال سریع -->
                    <div>
                        <h6 class="fw-bold">ارسال سریع و مطمئن</h6>
                        <p>سفارش خود را از هر نقطه‌ای ثبت کنید و در کمترین زمان ممکن تحویل بگیرید.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ردیف دوم (پشتیبانی همیشه در دسترس) -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="d-flex mb-4">
                    <i class="fas fa-headset fa-2x me-3"></i> <!-- آیکون برای پشتیبانی -->
                    <div>
                        <h6 class="fw-bold">پشتیبانی همیشه در دسترس</h6>
                        <p>تیم ما آماده پاسخگویی به سوالات شما در هر ساعت از شبانه‌روز است.</p>
                    </div>
                </div>
            </div>

            <!-- ردیف دوم (مشاهده بیشتر) -->
            <div class="col-md-6">
                <div class="d-flex mb-4">
                    <i class="fas fa-eye fa-2x me-3"></i> <!-- آیکون برای مشاهده -->
                    <div>
                        <h6 class="fw-bold">مشاهده بیشتر</h6>
                        <p>برای دیدن جزئیات بیشتر و سفارش، اینجا کلیک کنید.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش اطلاعات بیشتر در مورد سگ -->
    <div class="container mb-5">
        <h3 class="fw-bold mb-4">درباره ژرمن شپرد</h3>
        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. کتاب‌های زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان جامعه و متخصصان را می‌طلبد تا با نرم‌افزارها شناخت بیشتری را برای طراحان رایانه‌ای علی‌الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می‌توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساساً مورد استفاده قرار گیرد.</p>
    </div>

@endsection
