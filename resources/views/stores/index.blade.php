@extends('layouts.app')

@section('title', 'فروشگاه')

@section('content')

    <x-page-header
        title="فروشگاه"
        :breadcrumbs="[
            ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
            ['label' => 'فروشگاه', 'route' => route('frontend.stores.index')],
        ]"
        bgColor="linear-gradient(90deg, #0D47A1 0%, #051A3B 100%)"
    />

    <div class="container">
        <div class="row">
            <!-- فیلترها -->
            <div class="col-lg-4 col-md-5 col-12 mb-4">
                <x-filter-form :filters="$filters" />
            </div>

            <!-- آیتم‌ها -->
            <div class="col-md-8">
                <div class="row g-4">
                    @foreach($dogs as $dog)
                        <div class="col-md-4">
                            <x-dog-card :item="$dog" />
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                </div>
            </div>
        </div>
    </div>

<!-- Blog Slider Section -->
<div class="mt-5 container">
    <!-- Section Header with circle and divider -->
    <div class="d-flex align-items-center mb-4">
        <div style="width: 15px; height: 15px; border-radius: 50%; background-color: #0d47a1;"></div>
        <h3 class="fw-bold mb-0 ms-3">بلاگ‌ها</h3>
        <div class="flex-grow-1" style="height: 2px; background-color: #0d47a1;"></div>
    </div>

    <div class="position-relative">
        <div class="swiper blog-swiper px-3">
            <div class="swiper-wrapper">
                @foreach($blogs as $blog)
                    <div class="swiper-slide">
                        <div class="card h-100 border-0 shadow-sm d-flex flex-column">
                            <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('images/blog-placeholder.jpg') }}"
                                 class="card-img-top"
                                 alt="{{ $blog->title }}"
                                 style="object-fit: cover; height: 180px; width: 100%;">
                            <div class="card-body d-flex flex-column justify-content-between align-items-center text-center px-3" style="min-height: 190px;">
                                <h6 class="card-title">{{ Str::limit($blog->title, 50) }}</h6>
                                <p class="card-text text-muted small flex-grow-1"
                                   style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">
                                    {{ Str::limit($blog->description ?? $blog->content ?? '', 90) }}
                                </p>
                                <a href="{{ route('frontend.blog.show', $blog->slug) }}"
                                   class="btn btn-outline-primary btn-sm mt-2"
                                   style="border-color: #0d47a1; color: #0d47a1;">
                                    بیشتر بخوانید
                                </a>
                            </div>
                            <div style="border-bottom: 4px solid #0d47a1;"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Navigation Arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<style>
    /* Swiper slide fixed width and spacing */
    .swiper-slide {
        width: auto !important;
        max-width: 350px;
        padding: 0 15px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
    }

    /* Equal height cards using flex */
    .swiper-slide .card {
        height: 420px;
        display: flex;
        flex-direction: column;
    }

    /* Fixed image height */
    .swiper-slide .card-img-top {
        height: 180px;
        object-fit: cover;
        width: 100%;
        flex-shrink: 0;
    }

    /* Card body with fixed min-height and flex */
    .swiper-slide .card-body {
        min-height: 190px; /* fixes height */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        text-align: center;
        padding-left: 1rem;
        padding-right: 1rem;
        flex-grow: 1;
    }

    /* Clamp the description text lines to 4 lines */
    .swiper-slide .card-text {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
    }

    /* Larger navigation arrows with aura outside cards */
    .swiper-button-next,
    .swiper-button-prev {
        color: #0d47a1;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        box-shadow: 0 0 8px rgba(13, 71, 161, 0.7);
        top: 50%;
        transform: translateY(-50%);
    }

    .swiper-button-next {
        right: -60px; /* outside right */
    }

    .swiper-button-prev {
        left: -60px; /* outside left */
    }

    /* Increase arrow font size */
    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-size: 24px;
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    new Swiper('.blog-swiper', {
        slidesPerView: 3,
        spaceBetween: 30,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            0: {
                slidesPerView: 1.2,
                spaceBetween: 15,
            },
            576: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 3,
                spaceBetween: 30,
            }
        }
    });
</script>

<x-scripts.filter-submit />
@endpush
