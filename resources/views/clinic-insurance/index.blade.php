@extends('layouts.app')

@section('title', 'بیمه حیوانات')

@section('content')

<div class="mb-5 p-5 text-center text-white rounded" style="background-color: #5a8dee;">
    <h1 class="fw-bold mb-2">بیمه حیوانات</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center bg-transparent mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('frontend.clinic.insurance.index') }}" class="text-white text-decoration-none">بیمه حیوانات</a>
            </li>
        </ol>
    </nav>
</div>

<div class="container">
    <div class="row">
        <!-- بخش فیلتر سمت راست -->
        <div class="col-md-4"> <!-- اینجا عرض 1/3 صفحه را اختصاص داده‌ایم -->
            <div class="p-3 bg-light rounded mb-4">
                <h5 class="fw-bold">فیلترها</h5>

                <!-- فیلتر شهر -->
                <div class="mb-3">
                    <h6>شهر</h6>
                    <div>
                        <input type="checkbox" id="shiraz" value="shiraz">
                        <label for="shiraz" class="ms-2">شیراز</label>
                    </div>
                    <div>
                        <input type="checkbox" id="isfahan" value="isfahan">
                        <label for="isfahan" class="ms-2">اصفهان</label>
                    </div>
                    <div>
                        <input type="checkbox" id="tehran" value="tehran">
                        <label for="tehran" class="ms-2">تهران</label>
                    </div>
                    <div>
                        <input type="checkbox" id="mashhad" value="mashhad">
                        <label for="mashhad" class="ms-2">مشهد</label>
                    </div>
                    <div>
                        <input type="checkbox" id="tabriz" value="tabriz">
                        <label for="tabriz" class="ms-2">تبریز</label>
                    </div>
                </div>

            </div>

            <!-- فیلتر نژاد -->
            <div class="p-3 bg-light rounded mb-4">
                <h6>مدت زمان بیمه</h6>
                <div>
                    <input type="checkbox" id="germanshepherd" value="germanshepherd">
                    <label for="germanshepherd" class="ms-2">6 ماهه</label>
                </div>
                <div>
                    <input type="checkbox" id="husky" value="husky">
                    <label for="husky" class="ms-2">1 ساله</label>
                </div>
                <div>
                    <input type="checkbox" id="doberman" value="doberman">
                    <label for="doberman" class="ms-2">2 ساله</label>
                </div>
            </div>

        </div>

        <div class="col-md-8"> <!-- اینجا عرض 2/3 صفحه را اختصاص داده‌ایم -->
            <div class="row g-4">
                <!-- سگ 1 -->
                <div class="col-md-4">
                    <div class="card">
                        <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                        <div class="card-body">
                            <h6 class="fw-bold">بیمه زندگی</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                        <div class="card-body">
                            <h6 class="fw-bold">بیمه زندگی</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                        <div class="card-body">
                            <h6 class="fw-bold">بیمه زندگی</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                        <div class="card-body">
                            <h6 class="fw-bold">بیمه زندگی</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                        <div class="card-body">
                            <h6 class="fw-bold">بیمه زندگی</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                        <div class="card-body">
                            <h6 class="fw-bold">بیمه زندگی</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- دکمه مشاهده بیشتر -->
            <div class="text-center mt-4">
                <a href="#" class="btn btn-secondary">مشاهده بیشتر</a>
            </div>
        </div>

        <div class="service-box">
            <div class="row">
                <div class="col-md-8">
                    <div class="content">
                        <h5 class="fw-bold">خدمات و پوشش بیمه‌ای برای سگ شما</h5>
                        <p>
                            ما در پلتفرم سگ‌ها به صاحبان سگ‌ها کمک می‌کنیم تا بهترین خدمات و پوشش‌های بیمه‌ای را برای عزیزان چهارپای خود انتخاب کنند. با همکاری با معتبرترین کلینیک‌ها و شرکت‌های بیمه، ما به شما این امکان را می‌دهیم که به راحتی خدمات پزشکی، جراحی، واکسیناسیون، و سایر نیازهای بهداشتی سگ‌ها را مدیریت کنید. همچنین، با ارائه پوشش‌های بیمه‌ای جامع، خیال شما را از هرگونه هزینه‌های پیش‌بینی‌نشده راحت می‌کنیم. بهترین خدمات برای بهترین دوست شما، همیشه در دسترس است.
                        </p>
                        <ul class="services-list">
                            <li><a href="{{ route('frontend.clinics.index') }}">کلینیک‌ها</a></li>
                            <li><a href="#">مشاوره بیمه</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="image">
                        <img src="{{ asset('images/dogPicture.jpg') }}" alt="سگ">
                    </div>
                </div>
            </div>
        </div>

        <!-- اضافه کردن بخش نظرات -->
        <div class="comments-section mt-5">
            <h5 class="fw-bold">نظرات در مورد مستر روتوایلر</h5>

            <div class="comment mb-4">
                <h6 class="fw-bold">حمید حمیدی</h6>
                <p>لورم ایپسوم متن ساختگی با تولید سادگی لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.</p>
            </div>

            <div class="comment mb-4">
                <h6 class="fw-bold">حمید حمیدی</h6>
                <p>لورم ایپسوم متن ساختگی با تولید سادگی لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.</p>
            </div>

            <div class="text-center mt-4">
                <a href="#" class="btn btn-secondary">مشاهده بیشتر</a>
            </div>
        </div>

        <!-- اضافه کردن بخش سوالات متداول -->
        <div class="comments-section mt-5">
            <h3 class="fw-bold mb-4 text-center">سوالات متداول</h3>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">جلسات چقدر طول می‌کشد؟</h6>
                    <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">آیا امکان آموزش در خانه من هست؟</h6>
                    <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">مربیان با چه نژادهایی کار می‌کنند؟</h6>
                    <p>لورم ایپسوم متن ساختگی...</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">لورم ایپسوم متن ساختگی؟</h6>
                    <p>لورم ایپسوم متن ساختگی...</p>
                </div>
            </div>
        </div>


    </div>
</div>

@endsection
<style>
    .service-box {
        background-color: #f8f9fa; /* رنگ پس‌زمینه */
        border-radius: 15px; /* گوشه‌های گرد */
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* سایه برای برجسته‌تر کردن مستطیل */
    }

    .service-box .content {
        padding-right: 20px;
    }

    .service-box .content h5 {
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .service-box .content p {
        font-size: 1rem;
        line-height: 1.6;
        color: #6c757d;
    }

    .service-box .services-list {
        list-style-type: none;
        padding-left: 0;
        margin-top: 20px;
    }

    .service-box .services-list li {
        margin-bottom: 10px;
    }

    .service-box .services-list a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
    }

    .service-box .services-list a:hover {
        text-decoration: underline;
    }

    .service-box .image img {
        max-width: 100%;
        border-radius: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .comments-section {
        background-color: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .comments-section h5 {
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .comment {
        margin-bottom: 20px;
    }

    .comment h6 {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .comment p {
        font-size: 1rem;
        color: #6c757d;
        line-height: 1.6;
    }

    .comments-section .btn {
        margin-top: 20px;
    }
</style>
