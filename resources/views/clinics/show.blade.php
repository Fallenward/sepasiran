@extends('layouts.app')

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

@section('content')
@php
    $isClinic = $trainer->roles->pluck('name')->contains('clinic');
@endphp

<div class="container">
    <!-- Info Section -->
    <div class="card shadow-sm p-4 mb-5">
        <div class="row align-items-stretch">
            <div class="col-md-8 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="fw-bold mb-3">
                        درباره {{ $isClinic ? 'کلینیک' : 'مربی' }} {{ $trainer->name }} {{ $trainer->family }}
                    </h4>
                    <p class="text-muted">
                        {{ $trainer->bio ?? $trainer->description ?? 'اطلاعاتی ثبت نشده است.' }}
                    </p>
                </div>

                <div class="row g-3 mt-4">
                    <div class="col-6 d-flex">
                        <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">احراز هویت شده</h6>
                            <p class="mb-0 small">اطلاعات هویتی تایید شده است.</p>
                        </div>
                    </div>
                    <div class="col-6 d-flex">
                        <i class="fas fa-star fa-2x text-warning me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">تجربه بالا</h6>
                            <p class="mb-0 small">{{ $isClinic ? 'کلینیکی باسابقه و حرفه‌ای' : 'بیش از ۵ سال سابقه آموزش سگ‌ها.' }}</p>
                        </div>
                    </div>
                    <div class="col-6 d-flex">
                        <i class="fas fa-thumbs-up fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">رضایت مشتریان</h6>
                            <p class="mb-0 small">محبوبیت بالا بین کاربران.</p>
                        </div>
                    </div>
                    <div class="col-6 d-flex">
                        <i class="fas fa-certificate fa-2x text-info me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">دارای گواهینامه</h6>
                            <p class="mb-0 small">دارای مدارک معتبر از مراکز رسمی.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex flex-column align-items-center justify-content-center border-start">
                <img src="{{ $trainer->profile_picture ? asset('storage/' . $trainer->profile_picture) : asset('images/default-coach.png') }}"
                     alt="{{ $trainer->name }}"
                     class="rounded-circle mb-3" width="160" height="160" style="object-fit: cover;">
                <h5 class="fw-bold mb-3">{{ $trainer->name }} {{ $trainer->family }}</h5>
                <a href="tel:{{ $trainer->phone ?? '#' }}" class="btn btn-primary">تماس با {{ $isClinic ? 'کلینیک' : 'مربی' }}</a>
            </div>
        </div>
    </div>

    <!-- Specialties -->
    <div class="card shadow-sm p-4 mb-5">
        <h4 class="fw-bold mb-2">تخصص‌ها</h4>
        <div style="width: 60px; height: 4px; background-color: #0d47a1;" class="mb-3 rounded"></div>
        @if($trainer->speciality && $trainer->speciality->count())
            <div class="d-flex flex-wrap gap-2">
                @foreach($trainer->speciality as $spec)
                    <span class="badge bg-primary fs-6 px-3 py-2">{{ $spec->name }}</span>
                @endforeach
            </div>
        @else
            <p class="text-muted">تخصصی ثبت نشده است.</p>
        @endif
    </div>

    <!-- Services / Courses -->
    <div class="card shadow-sm p-4 mb-5">
        <h4 class="fw-bold mb-3">{{ $isClinic ? 'خدمات کلینیک' : 'دوره‌های مربی' }}</h4>
        <div style="width: 60px; height: 4px; background-color: #0d47a1;" class="mb-4 rounded"></div>

        @if($isClinic)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('images/service-1.png') }}" class="card-img-top" alt="خدمت ۱">
                        <div class="card-body">
                            <h5 class="card-title">معاینه تخصصی</h5>
                            <p class="card-text small text-muted">بررسی وضعیت سلامتی حیوانات توسط دامپزشک متخصص.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('images/service-2.png') }}" class="card-img-top" alt="خدمت ۲">
                        <div class="card-body">
                            <h5 class="card-title">واکسیناسیون</h5>
                            <p class="card-text small text-muted">تزریق واکسن‌های ضروری برای سلامت حیوان خانگی شما.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('images/service-3.png') }}" class="card-img-top" alt="خدمت ۳">
                        <div class="card-body">
                            <h5 class="card-title">جراحی و درمان</h5>
                            <p class="card-text small text-muted">ارائه خدمات جراحی‌های رایج با تجهیزات پیشرفته.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @php $items = $trainer->courses ?? []; @endphp
            @if(count($items))
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach($items as $item)
                            <div class="swiper-slide">
                                <div class="card h-100 shadow-sm">
                                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/course-placeholder.jpg') }}"
                                         class="card-img-top" alt="{{ $item->title }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $item->title }}</h5>
                                        <p class="card-text small text-muted">{{ Str::limit($item->description, 100) }}</p>
                                        <a href="{{ route('courses.show', $item->id) }}" class="btn btn-outline-primary btn-sm mt-2">مشاهده</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            @else
                <p class="text-muted">هیچ دوره‌ای برای این مربی ثبت نشده است.</p>
            @endif
        @endif
    </div>

    <!-- Products -->
    <div class="card shadow-sm p-4 mb-5">
        <h4 class="fw-bold mb-3">محصولات {{ $isClinic ? 'کلینیک' : 'مربی' }}</h4>
        <div style="width: 60px; height: 4px; background-color: #0d47a1;" class="mb-4 rounded"></div>

        @if($trainer->products && $trainer->products->count())
            <div class="swiper mySwiperProducts">
                <div class="swiper-wrapper">
                    @foreach($trainer->products as $product)
                        <div class="swiper-slide">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/product-placeholder.jpg') }}"
                                     class="card-img-top" alt="{{ $product->title }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->title }}</h5>
                                    <p class="card-text small text-muted">{{ Str::limit($product->description, 100) }}</p>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm mt-2">مشاهده محصول</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        @else
            <p class="text-muted">هیچ محصولی ثبت نشده است.</p>
        @endif
    </div>

    <!-- Gallery -->
    <div class="card shadow-sm p-4 mb-5">
        <h4 class="fw-bold mb-4">گالری تصاویر</h4>
        <div class="row g-3">
            <div class="col-md-8">
                <div class="card h-100">
                    <img src="{{ $gallery[0]->image ?? asset('images/gallery-placeholder.jpg') }}"
                         alt="Gallery Image"
                         class="card-img-top h-100 object-fit-cover"
                         style="min-height: 350px;">
                </div>
            </div>
            <div class="col-md-4 d-flex flex-column gap-3">
                <div class="card flex-fill">
                    <img src="{{ $gallery[1]->image ?? asset('images/gallery-placeholder.jpg') }}"
                         alt="Gallery Image"
                         class="card-img-top h-100 object-fit-cover"
                         style="min-height: 170px;">
                </div>
                <div class="card flex-fill">
                    <img src="{{ $gallery[2]->image ?? asset('images/gallery-placeholder.jpg') }}"
                         alt="Gallery Image"
                         class="card-img-top h-100 object-fit-cover"
                         style="min-height: 170px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Comments -->
    <div class="card shadow-sm p-4 mb-5">
        <h4 class="fw-bold mb-4">نظرات کاربران</h4>
        <ul class="list-unstyled">
            @foreach($comments as $comment)
                <li class="mb-4 d-flex align-items-start">
                    <img src="{{ $comment->user->avatar ?? asset('images/default-avatar.png') }}"
                         alt="{{ $comment->user->name ?? 'کاربر' }}"
                         class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
                    <div>
                        <h6 class="mb-1 fw-bold">{{ $comment->user->name ?? 'کاربر' }}</h6>
                        <div class="text-warning mb-2">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa fa-star"></i>
                            @endfor
                        </div>
                        <p>{{ $comment->content ?? '' }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

<!-- Swiper JS Config -->
<script>
    new Swiper('.mySwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            576: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            992: { slidesPerView: 3 },
        }
    });

    new Swiper('.mySwiperProducts', {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            576: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            992: { slidesPerView: 3 },
        }
    });
</script>
