@extends('layouts.app')

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>


@section('content')
<div class="container">
        <!-- Coach Info Section (Styled Card with Taller Right Side and Features Below) -->
        <div class="card shadow-sm p-4 mb-5">
            <div class="row align-items-stretch">
                <!-- Text Side (Left Visually in RTL) -->
                <div class="col-md-8 d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-3">درباره {{ $trainer->name }} {{ $trainer->family }}</h4>
                        <p class="text-muted">{{ $trainer->bio ?? 'اطلاعاتی درباره مربی ثبت نشده است.' }}</p>
                    </div>

                    <!-- Feature Section under Bio -->
                    <div class="row g-3 mt-4">
                        <div class="col-6 d-flex">
                            <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">احراز هویت شده</h6>
                                <p class="mb-0 small">این مربی اطلاعات هویتی خود را تایید کرده است.</p>
                            </div>
                        </div>
                        <div class="col-6 d-flex">
                            <i class="fas fa-star fa-2x text-warning me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">تجربه بالا</h6>
                                <p class="mb-0 small">بیش از ۵ سال سابقه آموزش و تربیت حرفه‌ای سگ‌ها.</p>
                            </div>
                        </div>
                        <div class="col-6 d-flex">
                            <i class="fas fa-thumbs-up fa-2x text-primary me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">رضایت مشتریان</h6>
                                <p class="mb-0 small">مربی محبوب کاربران با امتیازهای مثبت فراوان.</p>
                            </div>
                        </div>
                        <div class="col-6 d-flex">
                            <i class="fas fa-certificate fa-2x text-info me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">دارای گواهینامه</h6>
                                <p class="mb-0 small">دارای مدارک معتبر از مراکز آموزشی رسمی.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image and Action (Right Side) -->
                <div class="col-md-4 d-flex flex-column align-items-center justify-content-center border-start">
                    <img src="{{ $trainer->profile_picture
                                ? asset('storage/' . $trainer->profile_picture)
                                : asset('images/default-coach.png') }}"
                        alt="{{ $trainer->name }}"
                        class="rounded-circle mb-3" width="160" height="160" style="object-fit: cover;">

                    <h5 class="fw-bold mb-3">{{ $trainer->name }} {{ $trainer->family }}</h5>

                    <a href="tel:{{ $trainer->phone ?? '#' }}" class="btn btn-primary">تماس با مربی</a>
                </div>
            </div>
        </div>


                        <!-- Coach Specialties Section -->
                <div class="card shadow-sm p-4 mb-5">
                    <h4 class="fw-bold mb-2">تخصص‌ها</h4>

                    <!-- Thick Short Line -->
                    <div style="width: 60px; height: 4px; background-color: #0d47a1;" class="mb-3 rounded"></div>

                    @if($trainer->speciality && $trainer->speciality->count())
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($trainer->speciality as $spec)
                                <span class="badge bg-primary fs-6 px-3 py-2">{{ $spec->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">تخصصی برای این مربی ثبت نشده است.</p>
                    @endif
                </div>



                <!-- Coach Courses Section -->
<div class="card shadow-sm p-4 mb-5">
    <h4 class="fw-bold mb-3">دوره‌های مربی</h4>
    <div style="width: 60px; height: 4px; background-color: #0d47a1;" class="mb-4 rounded"></div>

    @if($trainer->courses && $trainer->courses->count())
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach($trainer->courses as $course)
                    <div class="swiper-slide">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/course-placeholder.jpg') }}"
                                 class="card-img-top" alt="{{ $course->title }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $course->title }}</h5>
                                <p class="card-text small text-muted">{{ Str::limit($course->description, 100) }}</p>
                                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-outline-primary btn-sm mt-2">مشاهده دوره</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Swiper Navigation Buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    @else
        <p class="text-muted">هیچ دوره‌ای برای این مربی ثبت نشده است.</p>
    @endif
</div>


<!-- Coach Products Section -->
<div class="card shadow-sm p-4 mb-5">
    <h4 class="fw-bold mb-3">محصولات مربی</h4>
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

            <!-- Swiper Navigation Buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    @else
        <p class="text-muted">هیچ محصولی برای این مربی ثبت نشده است.</p>
    @endif
</div>


<!-- Gallery Section -->
<div class="card shadow-sm p-4 mb-5">
    <h4 class="fw-bold mb-4">گالری تصاویر</h4>

    <div class="row g-3">
        <!-- Big Image on Left -->
        <div class="col-md-8">
            <div class="card h-100">
                <img src="{{ $gallery[0]->image ?? asset('images/gallery-placeholder.jpg') }}"
                     alt="Gallery Image"
                     class="card-img-top h-100 object-fit-cover"
                     style="min-height: 350px;">
            </div>
        </div>

        <!-- Two Small Images on Right stacked vertically -->
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


<!-- Comments Section -->
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

                    <!-- Star rating under name -->
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






    {{-- <!-- Medals Section -->
    <h3 class="mb-4">امتیازات {{ $trainer->name }}</h3>
    <div class="row text-center">
        @foreach($medals as $medal)
            <div class="col-6 col-md-2 mb-4">
                <img src="{{ asset('images/medals/' . $medal['icon']) }}" width="60" alt="{{ $medal['title'] }}">
                <p class="mt-2">{{ $medal['title'] }}</p>
            </div>
        @endforeach
    </div> --}}

</div>
@endsection

<script>
    const swiper = new Swiper('.mySwiper', {
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

