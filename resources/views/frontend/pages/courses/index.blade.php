@extends('layouts.app')

@section('title', $title ?? 'دوره‌ها')

@section('content')
<section class="sp-listing-hero">
    <div class="sp-listing-hero__bg" aria-hidden="true">
        <img src="{{ asset('images/demo/hero/hero-02.jpg') }}" alt="">
    </div>
    <div class="wrap sp-listing-hero__inner">
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>دوره‌ها</span>
        </nav>
        <div class="sp-listing-hero__title-row">
            <span class="sp-section-head__icon" aria-hidden="true"><x-sp-icon name="course" /></span>
            <h1>دوره‌ها</h1>
        </div>
        <p class="sp-listing-hero__lede">جستجو در عنوان و دسته‌بندی — دوره‌های آموزشی سپاسیران.</p>
    </div>
</section>

<div class="wrap sp-listing" style="padding-top:28px;padding-bottom:56px">
    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form
                :filters="$filters"
                search-placeholder="عنوان یا دسته‌بندی را بنویسید…"
                search-icon="course"
            />
        </div>

        <div class="sp-listing__results">
            <div class="grid grid-3">
                @forelse($courses as $course)
                    <div class="sp-listing-card">
                        <x-course-card :item="$course" />
                    </div>
                @empty
                    <div class="panel" style="grid-column:1/-1;text-align:center">
                        <p style="margin:0;color:var(--sp-text-muted)">دوره‌ای یافت نشد. عبارت جستجو یا فیلترها را تغییر دهید.</p>
                    </div>
                @endforelse
            </div>

            @if(method_exists($courses, 'hasPages') && $courses->hasPages())
                <nav class="sp-listing__pager" aria-label="صفحه‌بندی">
                    <div class="sp-pagination">
                        {{ $courses->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </nav>
            @endif
        </div>
    </div>
</div>

<x-scripts.filter-submit />
@endsection
