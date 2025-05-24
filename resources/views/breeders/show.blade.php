@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Coach Info Section -->
    <div class="card shadow-sm p-4 mb-5">
        <div class="row align-items-stretch">
            <!-- Text Side -->
            <div class="col-md-8 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="fw-bold mb-3">درباره {{ $trainer->name }} {{ $trainer->family }}</h4>
                    <p class="text-muted">{{ $trainer->bio ?? 'اطلاعاتی درباره مربی ثبت نشده است.' }}</p>
                </div>


            </div>

            <!-- Image and Contact -->
            <div class="col-md-4 d-flex flex-column align-items-center justify-content-center border-start">
                <img src="{{ $trainer->profile_picture ? asset('storage/' . $trainer->profile_picture) : asset('images/default-coach.png') }}"
                     alt="{{ $trainer->name }}"
                     class="rounded-circle mb-3" width="160" height="160" style="object-fit: cover;">

                <h5 class="fw-bold mb-3">{{ $trainer->name }} {{ $trainer->family }}</h5>
            </div>
        </div>
    </div>


            {{-- Section: Stats --}}
        <section class="statistics-section section-spacing">
            <div class="container">
                <div class="row g-4">
                    @foreach($statistics as $item)
                        <div class="col-6 col-md-6 col-lg-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <p class="card-text-counter fs-3 fw-bold text-primary">
                                        {{ $item['counter'] }}<i class="fa fa-plus ms-1"></i>
                                    </p>
                                    <h5 class="card-title-counter">{{ $item['title'] }}</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>




            <!-- Gallery Section -->
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

<div class="card shadow-sm p-4 mb-5">
    <h4 class="fw-bold mb-2 text-center" style="position: relative;">
        مدال‌های {{ $trainer->name }}
        <span style="display:block; width: 80px; height: 5px; background-color: #0d47a1; margin: 0.5rem auto 0; border-radius: 2px;"></span>
    </h4>

    <div class="row g-4 text-center">
        @foreach($medals as $index => $medal)
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <img src="{{ asset('images/medals.png') }}"
                         class="img-fluid mx-auto"
                         style="width: 100px; height: 100px; object-fit: contain;"
                         alt="{{ $medal['title'] }}">
                    <p class="mt-3 fw-bold">{{ $medal['title'] }}</p>
                    <small class="text-muted">{{ $medal['description'] }}</small>

                    <!-- Progress bar -->
                    <div class="progress mt-3" style="height: 8px; background-color: #e0e0e0; border-radius: 5px;">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ $medal['progress'] }}%; background-color: #0d47a1; border-radius: 5px;"
                             aria-valuenow="{{ $medal['progress'] }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>

            @if(($index + 1) % 3 == 0)
                <div class="w-100 d-none d-md-block"></div>
            @endif
        @endforeach
    </div>
</div>

{{-- Pending Dogs (مولد) --}}
<div class="card shadow-sm p-4 mb-5">
    <h4 class="fw-bold mb-2">سگ های مولد</h4>
    <div style="width: 60px; height: 4px; background-color: #0d47a1;" class="mb-3 rounded"></div>

    @if($dogsPending->count())
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($dogsPending as $dog)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('images/medals.png') }}" alt="Medal" class="card-img-top" style="width: 150px;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $dog->name }}</h5>
                            <p class="card-text small text-muted">{{ Str::limit($dog->description, 100) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">هیچ سگ مولدی ثبت نشده است.</p>
    @endif
</div>

    {{-- Ready Dogs for Sale --}}
    <div class="card shadow-sm p-4 mb-5">
        <h4 class="fw-bold mb-3">سگ های آماده فروش</h4>
        <div style="width: 60px; height: 4px; background-color: #0d47a1;" class="mb-4 rounded"></div>

        @if($dogsReady->count())
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($dogsReady as $dog)
                        <div class="swiper-slide">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ $dog->image ? asset('storage/' . $dog->image) : asset('images/dog-placeholder.jpg') }}"
                                     class="card-img-top" alt="{{ $dog->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $dog->name }}</h5>
                                    <p class="card-text small text-muted">{{ Str::limit($dog->description, 100) }}</p>
                                    {{-- Add link to details if needed --}}
                                    <a href="#" class="btn btn-outline-primary btn-sm mt-2">مشاهده سگ</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        @else
            <p class="text-muted">هیچ سگ آماده فروشی ثبت نشده است.</p>
        @endif
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


</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
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

    const swiperProducts = new Swiper('.mySwiperProducts', {
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

        <style>
    .statistics-section {
        background-color: #f5f5f5;
        padding: 3rem 0;
    }
    .card-text-counter {
        font-size: 2rem;
    }

        .row.text-center img {
        transition: transform 0.3s ease;
            }


    }
    </style>

    });
</script>
@endpush
