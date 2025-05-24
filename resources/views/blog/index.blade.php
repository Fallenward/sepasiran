@extends('layouts.app')

@section('template_title')
    وبلاگ
@endsection

@section('content')
<div class="container py-4">

<!-- بخش عنوان و نوار مسیریابی -->
<x-page-header
    title=" بلاگ ها"
    :breadcrumbs="[
        ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
        ['label' => 'بلاگ ها', 'route' => route('frontend.dog.supply.index')]
    ]"
    extraClasses="text-align-center"
    bgColor="#0d47a1"
/>

    <!-- Most Reviewed Blogs -->
    <div class="mt-5">
        <div class="d-flex align-items-center mb-4">
            <div style="width: 15px; height: 15px; border-radius: 50%; background-color: #0d47a1;"></div>
            <h5 class="fw-bold mb-0 ms-3">● پربیننده‌ترین‌ها</h5>
            <div class="flex-grow-1" style="height: 2px; background-color: #0d47a1;"></div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4 text-end">
            @foreach($mostReviewedBlogs as $blog)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm d-flex flex-column">
                        <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('images/blog-placeholder.jpg') }}"
                             class="card-img-top"
                             alt="{{ $blog->title }}"
                             style="object-fit: cover; height: 180px;">
                        <div class="card-body text-center d-flex flex-column justify-content-between" style="min-height: 190px;">
                            <h6 class="card-title">{{ Str::limit($blog->title, 50) }}</h6>
                            <p class="card-text text-muted small"
                               style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">
                                {{ Str::limit($blog->description ?? $blog->content ?? '', 90) }}
                            </p>
                            <a href="{{ route('frontend.blog.show', $blog->slug) }}" class="btn btn-outline-primary btn-sm mt-2">
                                بیشتر بخوانید
                            </a>
                        </div>
                        <div style="border-bottom: 4px solid #0d47a1;"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Divider -->
    <div class="position-relative text-center my-5">
        <hr>
        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">ادامه مطالب</span>
    </div>

    <!-- Latest Blogs -->
    <div class="mb-5">
        <div class="d-flex align-items-center mb-4">
            <div style="width: 15px; height: 15px; border-radius: 50%; background-color: #0d47a1;"></div>
            <h5 class="fw-bold mb-0 ms-3">● آخرین مطالب</h5>
            <div class="flex-grow-1" style="height: 2px; background-color: #0d47a1;"></div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4 text-end">
            @foreach($latestBlogs as $blog)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm d-flex flex-column">
                        <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('images/blog-placeholder.jpg') }}"
                             class="card-img-top"
                             alt="{{ $blog->title }}"
                             style="object-fit: cover; height: 180px;">
                        <div class="card-body text-center d-flex flex-column justify-content-between" style="min-height: 190px;">
                            <h6 class="card-title">{{ Str::limit($blog->title, 50) }}</h6>
                            <p class="card-text text-muted small"
                               style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">
                                {{ Str::limit($blog->description ?? $blog->content ?? '', 90) }}
                            </p>
                            <a href="{{ route('frontend.blog.show', $blog->slug) }}" class="btn btn-outline-primary btn-sm mt-2">
                                بیشتر بخوانید
                            </a>
                        </div>
                        <div style="border-bottom: 4px solid #0d47a1;"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- See More Button -->
    <div class="text-center mt-4">
        <a href="{{ route('frontend.blog.index') }}" class="btn btn-outline-primary">مشاهده بیشتر</a>
    </div>
</div>
@endsection
