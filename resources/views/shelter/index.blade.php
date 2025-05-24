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
<div class="container my-5">
    <div class="card shadow-sm p-4" style="min-height: 500px;">
        <div class="row g-4">
            <!-- Text Section -->
            <div class="col-md-6 d-flex flex-column justify-content-between">
                <div>
                    <h3 class="fw-bold mb-4">اطلاعات پناهگاه</h3>
                    <p>
                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است،
                        چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز،
                        و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می‌باشد...
                    </p>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('frontend.shelter.index') }}" class="btn btn-primary">
                        مشارکت در پناهگاه
                    </a>
                    <a href="{{ route('frontend.shelter.index') }}" class="btn btn-outline-primary">
                        مشاهده تصاویر
                    </a>
                </div>
            </div>

            <!-- Image Section -->
            <div class="col-md-6 d-flex align-items-center justify-content-center">
                <img src="{{ asset('storage/dogPicture.jpg') }}" class="img-fluid rounded-4 shadow-sm" alt="تصویر پناهگاه" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>



<!-- بخش خدمات پناهگاه -->
<section class="services-section my-5">
    <div class="container">
        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="card-body">
                        <!-- Title and Button Row -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">پذیرش سگ‌ بی‌سرپرست</h5>
                            <a href="{{ route('frontend.services.show', 1) }}" class="btn btn-outline-primary btn-sm">مشاهده</a>
                        </div>
                        <!-- Content -->
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/icons/adoption.svg') }}" alt="آیکون پذیرش" style="width: 40px; height: 40px;" class="me-3">
                            <p class="mb-0">
                                این سرویس مخصوص سگ‌هایی است که بی‌سرپرست بوده و نیاز به پناهگاه، غذا و مراقبت‌های اولیه دارند.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">واکسیناسیون و معاینات پزشکی</h5>
                            <a href="{{ route('frontend.services.show', 2) }}" class="btn btn-outline-primary btn-sm">مشاهده</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/icons/vaccine.svg') }}" alt="آیکون واکسن" style="width: 40px; height: 40px;" class="me-3">
                            <p class="mb-0">
                                ارائه خدمات بهداشتی از جمله واکسن‌ها و بررسی‌های دوره‌ای برای حفظ سلامت سگ‌ها.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">فرآیند واگذاری سگ</h5>
                            <a href="{{ route('frontend.services.show', 3) }}" class="btn btn-outline-primary btn-sm">مشاهده</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/icons/adoption-process.svg') }}" alt="آیکون واگذاری" style="width: 40px; height: 40px;" class="me-3">
                            <p class="mb-0">
                                اگر قصد دارید سگی را به سرپرستی بگیرید، این سرویس مراحل کامل پذیرش را برای شما توضیح می‌دهد.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">آموزش و رفتارشناسی</h5>
                            <a href="{{ route('frontend.services.show', 4) }}" class="btn btn-outline-primary btn-sm">مشاهده</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/icons/training.svg') }}" alt="آیکون آموزش" style="width: 40px; height: 40px;" class="me-3">
                            <p class="mb-0">
                                ارائه خدمات آموزشی برای تربیت بهتر سگ‌ها و اصلاح رفتارهای نامطلوب آن‌ها.
                            </p>
                        </div>
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

{{-- Section: FAQ --}}
<section class="section-spacing text-center">
    <h2 class="fw-bold section-title">سوالات متداول</h2>

    <div class="bg-light rounded overflow-hidden mt-4">
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading1">
                    <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                        جلسات چقدر طول می‌کشد؟
                    </button>
                </h2>
                <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted text-end">
                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...
                    </div>
                </div>
            </div>

            @foreach ([
                'آیا امکان آموزش در خانه من هست؟',
                'مربیان با چه نژادهایی کار می‌کنند؟',
                'لورم ایپسوم متن ساختگی؟',
                'لورم ایپسوم متن ساختگی؟'
            ] as $index => $question)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $index + 2 }}">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index + 2 }}">
                            {{ $question }}
                        </button>
                    </h2>
                    <div id="collapse{{ $index + 2 }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted text-end">
                            پاسخ مربوط به سوال: "{{ $question }}"
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>



<style>
    /* Thick blue underline for the section title */
    .section-title {
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
        margin-bottom: 2rem;
    }

    .section-title::after {
        content: "";
        display: block;
        width: 120px;
        height: 5px;
        background-color: #0d47a1;
        border-radius: 3px;
        margin: 10px auto 0;
    }

    /* Light blue background for the open/active accordion item */
    .accordion-button:not(.collapsed) {
        background-color: #e3f2fd; /* Light blue */
        color: #000;
    }

    .accordion-button:focus {
        box-shadow: none;
    }
</style>

@endsection
