@extends('layouts.app')

@section('title', 'مربیان')

@section('content')
<x-page-header
    title="مربیان"
    :breadcrumbs="[
        ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
        ['label' => 'مربیان', 'route' => route('frontend.coaches.index')]
    ]"
    extraClasses="text-align-center"
    bgColor="#0d47a1"
/>

<div class="container">
    <div class="row">
        <!-- Filters -->
        <div class="col-md-4 col-12">
            <x-filter-form :filters="$filters" />
        </div>

        <!-- Coaches List -->
        <div class="col-md-8 col-12">
            <div class="row g-4" id="coaches-list">
                @foreach($coaches as $coach)
                    <div class="col-md-4 col-12">
                        <x-coach-card :item="$coach" />
                    </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            <x-load-more-button :hasMore="$coaches->hasMorePages()" :nextPage="$coaches->currentPage() + 1" />
        </div>
    </div>
</div>



<!-- Card Section -->
<div class="card shadow-sm p-3 mb-4 w-100" style="height: 480px;">
    <div class="d-flex flex-column flex-md-row align-items-stretch h-100">
        <!-- Text Content (Left) -->
        <div class="w-100 w-md-50 pe-md-4 d-flex flex-column justify-content-between h-100">
            <div class="card-body p-3">
                <h5 class="card-title fw-bold mb-3">عنوان کارت</h5>
                <p class="card-text mb-4" style="font-size: 1rem;">
                    توضیحاتی درباره این بخش که می‌تواند شامل جزئیات بیشتری درباره موضوع کارت باشد. این متن کمی طولانی‌تر است تا با ارتفاع تصویر هماهنگ شود.
                </p>
            </div>
            <div class="d-flex justify-content-start mb-3 px-3">
                <a href="#" class="btn btn-primary me-2">مشاوره مربی</a>
                <a href="#" class="btn btn-outline-primary">مربی ها</a>
            </div>
        </div>

        <!-- Image (Right) -->
        <div class="w-100 w-md-50 d-flex justify-content-center align-items-center p-3 h-100">
            <img src="{{ asset('images/dogPicture.jpg') }}" alt="تصویر کارت" class="img-fluid rounded" style="max-width: 2000px; max-height: 100%;">
        </div>
    </div>
</div>

<x-scripts.filter-submit />
@endsection
