@extends('layouts.app')

@section('title', 'پناهگاه')

@section('content')

    <!-- بخش عنوان و نوار مسیریابی -->
    {{-- <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #5a8dee;">
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
    </div> --}}

    <!-- بخش اطلاعات پناهگاه -->
<div class="container mb-5">
    <div class="row g-4">
        <!-- Card 1: Shelter Info with Button -->
 <div class="container my-5">
    <div class="row g-4">
        <!-- Card 1: Shelter Info with Button -->
        <div class="col-md-6">
            <div class="card shadow-sm p-4" style="min-height: 500px;">
                <div class="card-body d-flex flex-column justify-content-between h-100">
                    <div>
                        <h3 class="fw-bold mb-4">اطلاعات پناهگاه</h3>
                        <p>
                            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است،
                            چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز،
                            و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می‌باشد...
                        </p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('frontend.shelter.index') }}" class="btn btn-primary">
                            مشاهده تصاویر
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Shelter Image -->
        <div class="col-md-6">
            <div class="card shadow-sm p-4 d-flex align-items-center justify-content-center" style="min-height: 500px;">
                <img src="{{ asset('storage/dogPicture.jpg') }}" class="img-fluid rounded" alt="تصویر پناهگاه">
            </div>
        </div>
    </div>
</div>

        <!-- Card 2: Shelter Image Only -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm p-4"> <!-- Add padding inside the card -->
                <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top img-fluid rounded" alt="تصویر پناهگاه">
            </div>
        </div>
    </div>
</div>



<!-- بخش خدمات پناهگاه -->
<section class="services-section my-5">
    <div class="container">
        <div class="row g-4">
            <!-- Service Card 1 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-3">پذیرش سگ‌ بی‌سرپرست</h5>
                        <p class="card-text">
                            این سرویس مخصوص سگ‌هایی است که بی‌سرپرست بوده و نیاز به پناهگاه، غذا و مراقبت‌های اولیه دارند.
                        </p>
                        <a href="{{ route('frontend.services.show', 1) }}" class="btn btn-outline-primary mt-auto">
                            مشاهده بیشتر
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service Card 2 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-3">واکسیناسیون و معاینات پزشکی</h5>
                        <p class="card-text">
                            ارائه خدمات بهداشتی از جمله واکسن‌ها و بررسی‌های دوره‌ای برای حفظ سلامت سگ‌ها.
                        </p>
                        <a href="{{ route('frontend.services.show', 2) }}" class="btn btn-outline-primary mt-auto">
                            مشاهده بیشتر
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service Card 3 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-3">فرآیند واگذاری سگ</h5>
                        <p class="card-text">
                            اگر قصد دارید سگی را به سرپرستی بگیرید، این سرویس مراحل کامل پذیرش را برای شما توضیح می‌دهد.
                        </p>
                        <a href="{{ route('frontend.services.show', 3) }}" class="btn btn-outline-primary mt-auto">
                            مشاهده بیشتر
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service Card 4 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-3">آموزش و رفتارشناسی</h5>
                        <p class="card-text">
                            ارائه خدمات آموزشی برای تربیت بهتر سگ‌ها و اصلاح رفتارهای نامطلوب آن‌ها.
                        </p>
                        <a href="{{ route('frontend.services.show', 4) }}" class="btn btn-outline-primary mt-auto">
                            مشاهده بیشتر
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



    <!-- بخش سگ‌ها -->
    {{-- <div class="container mb-5">
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
    </div> --}}

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

    <div class="container my-5">
    <h3 class="fw-bold mb-4">گالری تصاویر</h3>

    <div class="bg-light p-4 py-5 rounded shadow-sm">
        <div class="row g-3">
            <!-- Image 1 -->
            <div class="col-md-4 p-3">
                <img src="{{ asset('storage/gallery1.jpg') }}" class="img-fluid rounded" style="height: 350px; object-fit: cover;" alt="تصویر 1">
            </div>

            <!-- Image 2 -->
            <div class="col-md-8 p-3">
                <img src="{{ asset('storage/gallery2.jpg') }}" class="img-fluid rounded" style="height: 350px; object-fit: cover;" alt="تصویر 2">
            </div>

            <!-- Image 3 -->
            <div class="col-md-6 p-3">
                <img src="{{ asset('storage/gallery3.jpg') }}" class="img-fluid rounded" style="height: 300px; object-fit: cover;" alt="تصویر 3">
            </div>

            <!-- Image 4 -->
            <div class="col-md-3 p-3">
                <img src="{{ asset('storage/gallery4.jpg') }}" class="img-fluid rounded-circle" style="height: 300px; object-fit: cover;" alt="تصویر 4">
            </div>

            <!-- Image 5 -->
            <div class="col-md-3 p-3">
                <img src="{{ asset('storage/gallery5.jpg') }}" class="img-fluid rounded" style="height: 300px; object-fit: cover;" alt="تصویر 5">
            </div>

            <!-- Image 6 -->
            <div class="col-md-4 p-3">
                <img src="{{ asset('storage/gallery6.jpg') }}" class="img-fluid rounded" style="height: 300px; object-fit: cover;" alt="تصویر 6">
            </div>

            <!-- Image 7 -->
            <div class="col-md-4 p-3">
                <img src="{{ asset('storage/gallery7.jpg') }}" class="img-fluid rounded-3" style="height: 350px; object-fit: cover;" alt="تصویر 7">
            </div>

            <!-- Image 8 -->
            <div class="col-md-4 p-3">
                <img src="{{ asset('storage/gallery8.jpg') }}" class="img-fluid rounded-top" style="height: 350px; object-fit: cover;" alt="تصویر 8">
            </div>

            <!-- Image 9 -->
            <div class="col-md-12 p-3">
                <img src="{{ asset('storage/gallery9.jpg') }}" class="img-fluid rounded" style="height: 400px; object-fit: cover; width: 100%;" alt="تصویر 9">
            </div>
        </div>
    </div>
</div>


    <!-- بخش سوالات متداول -->
<div class="container mb-5">
    <h3 class="fw-bold mb-4">سوالات متداول</h3>

    <div class="bg-light p-4 rounded shadow-sm">
        <div class="mb-4">
            <h6 class="fw-bold">چطور می‌توانم به پناهگاه کمک کنم؟</h6>
            <p>شما می‌توانید با اهدای غذا، وسایل مورد نیاز یا حمایت مالی از پناهگاه حمایت کنید.</p>
        </div>

        <div class="mb-4">
            <h6 class="fw-bold">چه نوع حیواناتی در این پناهگاه نگهداری می‌شوند؟</h6>
            <p>ما عمدتاً از سگ‌ها و گاهی گربه‌های بی‌سرپرست مراقبت می‌کنیم.</p>
        </div>

        <div class="mb-4">
            <h6 class="fw-bold">چطور می‌توانم به عنوان داوطلب ثبت‌نام کنم؟</h6>
            <p>از طریق فرم ثبت‌نام داوطلب در وب‌سایت می‌توانید اطلاعات خود را ارسال کرده و با ما همکاری کنید.</p>
        </div>

        <div>
            <h6 class="fw-bold">آیا می‌توانم حیوانی را به سرپرستی قبول کنم؟</h6>
            <p>بله، پس از بررسی شرایط شما و حیوان، فرآیند سرپرستی انجام می‌شود.</p>
        </div>
    </div>
</div>


@endsection
