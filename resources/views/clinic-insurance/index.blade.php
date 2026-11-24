@extends('layouts.app')

@section('title', 'بیمه حیوانات')

@section('content')
<section class="sp-listing-hero">
    <div class="sp-listing-hero__bg" aria-hidden="true">
        <img src="{{ asset('images/demo/hero/hero-03.jpg') }}" alt="">
    </div>
    <div class="wrap sp-listing-hero__inner">
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>بیمه حیوانات</span>
        </nav>
        <div class="sp-listing-hero__title-row">
            <span class="sp-section-head__icon" aria-hidden="true"><x-sp-icon name="insurance" /></span>
            <h1>بیمه حیوانات</h1>
        </div>
        <p class="sp-listing-hero__lede">جستجو در نام و پوشش — طرح را بخرید و مجوز مدت‌دار را در داشبورد ببینید.</p>
        @auth
            <p class="sp-listing-hero__lede" style="margin-top:10px">
                <a href="{{ route('account.insurance-licenses.index') }}" style="color:inherit;text-decoration:underline;text-underline-offset:3px">مجوزهای بیمه من</a>
            </p>
        @endauth
    </div>
</section>

<div class="wrap sp-listing" style="padding-top:28px;padding-bottom:56px">
    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form :filters="$filters" />
        </div>

        <div class="sp-listing__results">
            <div class="grid grid-3">
                @forelse($insurances as $insurance)
                    <div class="sp-listing-card">
                        <x-insurance-card :item="$insurance" />
                    </div>
                @empty
                    <div class="panel" style="grid-column:1/-1;text-align:center">
                        <p style="margin:0;color:var(--sp-text-muted)">بیمه‌ای یافت نشد. عبارت جستجو یا فیلترها را تغییر دهید.</p>
                    </div>
                @endforelse
            </div>

            @if(method_exists($insurances, 'hasPages') && $insurances->hasPages())
                <nav class="sp-listing__pager" aria-label="صفحه‌بندی">
                    <div class="sp-pagination">
                        {{ $insurances->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </nav>
            @endif
        </div>
    </div>
</div>

<x-scripts.filter-submit />
@endsection
