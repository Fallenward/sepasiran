@extends('layouts.app')

@section('title', 'سگ‌ها')

@section('content')
<section class="sp-listing-hero">
    <div class="sp-listing-hero__bg" aria-hidden="true">
        <img src="{{ asset('images/demo/hero/hero-01.jpg') }}" alt="">
    </div>
    <div class="wrap sp-listing-hero__inner">
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>سگ‌ها</span>
        </nav>
        <div class="sp-listing-hero__title-row">
            <span class="sp-section-head__icon" aria-hidden="true"><x-sp-icon name="dogs" /></span>
            <h1>سگ‌ها</h1>
        </div>
        <p class="sp-listing-hero__lede">جستجو در نژاد، شهر و نام — فیلتر تایپی با نتایج زنده از دیتابیس.</p>
    </div>
</section>

<div class="wrap sp-listing" style="padding-top:28px;padding-bottom:56px">
    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form :filters="$filters" :suggest-url="route('frontend.dog.supply.suggest')" />
        </div>

        <div class="sp-listing__results">
            <div id="dogs-list" class="grid grid-3">
                @forelse($dogs as $dog)
                    <div class="sp-listing-card">
                        @include('components.dog-card', ['item' => $dog])
                    </div>
                @empty
                    <div class="panel" style="grid-column:1/-1;text-align:center">
                        <p style="margin:0;color:var(--sp-text-muted)">سگی یافت نشد. عبارت جستجو یا فیلترها را تغییر دهید.</p>
                    </div>
                @endforelse
            </div>

            <div class="sp-listing__pager">
                <x-load-more-button :hasMore="$dogs->hasMorePages()" :nextPage="$dogs->currentPage() + 1" />
                @if(method_exists($dogs, 'links'))
                    <div class="sp-pagination">
                        {{ $dogs->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<x-scripts.filter-submit />
<x-scripts.load-more-dogs />
@endsection