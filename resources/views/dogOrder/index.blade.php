@extends('layouts.app')

@section('title', 'ثبت سفارش سگ')

@section('content')


    <!-- بخش اصلی ثبت سفارش -->
<div class="container mb-5">
    <div class="row">
        <!-- بخش اطلاعات سگ (سمت چپ) - Now moved to the right side -->
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <!-- Title -->
                <h5 class="fw-bold mb-4 text-left">مشخصات سگ</h5>

                <!-- Dog Info List (Stacked) -->
                <ul class="list-unstyled mb-4">
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>رنگ:</strong> مشکی</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>سن:</strong> 6 ماه</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>تاریخ تولد:</strong> 1403</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>نوع پوشش:</strong> چرمی</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>رنگ چشم:</strong> مشکی</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>شجره:</strong> دارد</span>
                    </li>
                </ul>

                <!-- Feature Section (2 at Top and 2 at Bottom) -->
                <div class="row">
                    <!-- First Row (تضمین قیمت and ارسال سریع و مطمئن side by side) -->
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="fas fa-shield-alt fa-2x text-primary me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">تضمین قیمت</h6>
                                <p class="mb-0">خرید مستقیم از پرورش‌دهندگان معتبر با تضمین مناسب‌ترین قیمت.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="fas fa-truck fa-2x text-primary me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">ارسال سریع و مطمئن</h6>
                                <p class="mb-0">سفارش خود را از هر نقطه‌ای ثبت کنید و در کمترین زمان ممکن تحویل بگیرید.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Row (محبت و مراقبت and خدمات پس از فروش stacked one under the other) -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="fas fa-heart fa-2x text-primary me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">محبت و مراقبت</h6>
                                <p class="mb-0">توجه ویژه به سلامت و رفاه سگ‌ها با محیطی امن و مراقبتی.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="fas fa-cogs fa-2x text-primary me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">خدمات پس از فروش</h6>
                                <p class="mb-0">پشتیبانی مداوم برای اطمینان از سلامت و راحتی سگ‌ها.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Section for پشتیبانی همیشه در دسترس and مشاهده بیشتر -->
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-headset fa-2x text-primary me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">پشتیبانی همیشه در دسترس</h6>
                                <p class="mb-0">تیم ما آماده پاسخگویی به سوالات شما در هر ساعت از شبانه‌روز است.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-arrow-circle-right fa-2x text-primary me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">مشاهده بیشتر</h6>
                                <p class="mb-0">برای دیدن جزئیات بیشتر و سفارش، اینجا کلیک کنید.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- بخش اطلاعات سگ (سمت راست) - Now moved to the left side -->
        <div class="col-md-6">
            <div class="card">
                <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                <div class="card-body text-center">
                    <h5 class="fw-bold">ژرمن شپرد</h5>
                    <a href="#" class="btn btn-outline-primary btn-sm">تماس</a>
                    <a href="#" class="btn btn-primary btn-sm">ثبت سفارش</a>
                </div>
            </div>
        </div>
    </div>
</div>



        <!-- ردیف دوم (پشتیبانی همیشه در دسترس) -->

    </div>

    <!-- بخش اطلاعات بیشتر در مورد سگ -->
<div class="container mb-5">
    <div class="bg-light p-4 rounded shadow-sm">
        <h3 class="fw-bold mb-4">درباره ژرمن شپرد</h3>
        <p>
            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است،
            چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی
            مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. کتاب‌های زیادی در شصت و سه
            درصد گذشته، حال و آینده شناخت فراوان جامعه و متخصصان را می‌طلبد تا با نرم‌افزارها شناخت بیشتری
            را برای طراحان رایانه‌ای علی‌الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این
            صورت می‌توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد
            و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی
            اساساً مورد استفاده قرار گیرد.
        </p>
    </div>
</div>


@endsection
