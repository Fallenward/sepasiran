@extends('layouts.app')

@section('title', 'پناهگاه')

@section('content')

    <!-- بخش عنوان و نوار مسیریابی -->
    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #5a8dee;">
        <h1 class="fw-bold mb-2">پناهگاه</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.shelter.index') }}" class="text-white text-decoration-none">پناهگاه</a>
                </li>
            </ol>
        </nav>
    </div>

    <!-- بخش اطلاعات پناهگاه -->
    <div class="container mb-5">
        <h3 class="fw-bold mb-4">اطلاعات پناهگاه</h3>
        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد،</p>

        <h4 class="fw-bold mb-3">گالری تصاویر</h4>
        <div class="row g-4">
            <div class="col-md-4">
                <img src="{{ asset('storage/dogPicture.jpg') }}" class="img-fluid rounded" alt="تصویر1">
            </div>
            <div class="col-md-4">
                <img src="{{ asset('storage/dogPicture.jpg') }}" class="img-fluid rounded" alt="تصویر2">
            </div>
            <div class="col-md-4">
                <img src="{{ asset('storage/dogPicture.jpg') }}" class="img-fluid rounded" alt="تصویر3">
            </div>
        </div>
    </div>

    <!-- بخش سگ‌ها -->
    <div class="container mb-5">
        <h3 class="fw-bold mb-4">سگ‌های موجود در پناهگاه</h3>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                    <div class="card-body">
                        <h6 class="fw-bold">سگ‌های تغذیه‌شده</h6>
                        <p class="text-muted small">ما به سگ‌های بی‌سرپرست غذاهای سالم و کافی می‌دهیم تا از سوءتغذیه و بیماری‌ها جلوگیری کنیم.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">مشاهده</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                    <div class="card-body">
                        <h6 class="fw-bold">عقیم کردن سگ‌ها</h6>
                        <p class="text-muted small">با عقیم‌سازی سگ‌ها، به کنترل جمعیت و پیشگیری از تولد توله‌های بی‌خانمان کمک می‌کنیم.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">مشاهده</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                    <div class="card-body">
                        <h6 class="fw-bold">سگ‌هایی که سرپرست پیدا کردند</h6>
                        <p class="text-muted small">تعداد زیادی سگ به خانواده‌های جدید سپرده شده‌اند تا در یک خانه امن و پر از محبت زندگی کنند.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">مشاهده</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                    <div class="card-body">
                        <h6 class="fw-bold">واکسیناسیون سگ‌ها</h6>
                        <p class="text-muted small">واکسیناسیون سگ‌ها، راهی برای محافظت از آنها در برابر بیماری‌های خطرناک و تضمین سلامت عمومی است.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">مشاهده</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش مشارکت در پناهگاه -->
    <div class="container mb-5">
        <h3 class="fw-bold mb-4">مشارکت در پناهگاه</h3>
        <div class="row">
            <!-- همراهی حضوری با پناهگاه -->
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <i class="fas fa-hands-helping fa-3x mb-3"></i> <!-- آیکون برای همراهی حضوری -->
                    <h6 class="fw-bold">همراهی حضوری با پناهگاه</h6>
                    <p>برای کمک حضوری و مشارکت در فعالیت‌های حمایتی پناهگاه، با ثبت‌نام در این بخش به تیم داوطلبان ما بپیوندید.</p>
                </div>
            </div>

            <!-- استفاده از امتیازهای خود -->
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <i class="fas fa-gift fa-3x mb-3"></i> <!-- آیکون برای استفاده از امتیازها -->
                    <h6 class="fw-bold">استفاده از امتیازهای خود</h6>
                    <p>اگر امتیاز جمع کرده‌اید، می‌توانید از آن برای تخفیف یا مشارکت در پروژه‌ها استفاده کنید.</p>
                </div>
            </div>

            <!-- حمایت مالی از پناهگاه -->
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <i class="fas fa-donate fa-3x mb-3"></i> <!-- آیکون برای حمایت مالی -->
                    <h6 class="fw-bold">حمایت مالی از پناهگاه</h6>
                    <p>کمک‌های مالی شما می‌تواند تغییرات بزرگی ایجاد کند. مبلغ دلخواه خود را انتخاب کنید یا مبلغ خاصی وارد کنید.</p>
                </div>
            </div>
        </div>
    </div>


    <!-- بخش سوالات متداول -->
    <div class="container mb-5">
        <h3 class="fw-bold mb-4">سوالات متداول</h3>
        <div class="row">
            <div class="col-md-6">
                <h6>چطور می‌توانم به پناهگاه کمک کنم؟</h6>
                <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
            </div>
            <div class="col-md-6">
                <h6>چه نوع حیواناتی در این پناهگاه نگهداری می‌شوند؟</h6>
                <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
            </div>
            <div class="col-md-6">
                <h6>چطور می‌توانم به عنوان داوطلب ثبت‌نام کنم؟</h6>
                <p>لورم ایپسوم متن ساختگی...</p>
            </div>
            <div class="col-md-6">
                <h6>آیا می‌توانم حیوانی را به سرپرستی قبول کنم؟</h6>
                <p>لورم ایپسوم متن ساختگی...</p>
            </div>
        </div>
    </div>

@endsection
