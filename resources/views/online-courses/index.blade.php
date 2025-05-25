@extends('layouts.app')

@section('title', 'دوره‌های آنلاین')

@section('content')

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

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

<!-- نوار بالایی با لینک‌ها -->
<div class="container mb-5">
    <h3 class="fw-bold mb-4">دوره های آنلاین</h3>

    <!-- Inserted category links here -->
    <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
        <a href="{{ route('frontend.online.courses') }}" class="border px-4 py-2 text-decoration-none text-dark rounded">همه دوره‌ها</a>
        <a href="#" class="border px-4 py-2 text-decoration-none text-dark rounded">دوره‌های گله‌داری</a>
        <a href="#" class="border px-4 py-2 text-decoration-none text-dark rounded">دوره‌های تشخیص بیماری</a>
        <a href="#" class="border px-4 py-2 text-decoration-none text-dark rounded">دوره‌های امداد و آتش‌نشانی</a>
        <a href="#" class="border px-4 py-2 text-decoration-none text-dark rounded">دوره‌های نگهبانی</a>
    </div>

<div class="swiper">
    <div class="swiper-wrapper">
        @foreach($courses as $course)
            <div class="swiper-slide" style="width: 250px;">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $course->image_path) }}" class="card-img-top" alt="دوره {{ $course->title }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold">{{ $course->title }}</h6>
                        <p class="text-muted small mb-3">{{ Str::limit($course->description, 60) }}</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span>تومان {{ number_format($course->price) }}</span>
                            <a href="{{ route('frontend.show', $course->id) }}" class="btn btn-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.swiper', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            freeMode: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }
        });
    });
</script>

<!-- بخش دوره های محبوب -->
<div class="container mb-5">
    <h3 class="fw-bold mb-4">دوره های محبوب</h3>
    <div class="row g-4 justify-content-center">
        @foreach(range(1, 4) as $i)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100" style="width: 100%; max-width: 250px; margin: 0 auto;">
                    <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="محبوب">
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold">دوره محبوب {{ $i }}</h6>
                        <p class="text-muted small mb-3">توضیحات نمونه برای دوره محبوب {{ $i }}...</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span>تومان 500,000</span>
                            <a href="#" class="btn btn-primary btn-sm">مشاهده دوره</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


<!-- بخش نظرات -->
<div class="container mb-6">
    <h3 class="fw-bold mb-4">نظرات در مورد مستر روتوایلر</h3>
    <div class="row g-4">
        @foreach(range(1,2) as $i)
            <div class="col-12">
                <div class="border rounded p-4 shadow-sm bg-white h-100">
                    <h6 class="fw-bold mb-2">حمید حمیدی</h6>
                    <p class="mb-0">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center mt-4">
        <a href="#" class="btn btn-outline-primary">مشاهده بیشتر</a>
    </div>
</div>


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
