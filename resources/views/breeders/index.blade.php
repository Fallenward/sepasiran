@extends('layouts.app')

@section('title', 'پرورش‌دهندگان')

@section('content')
<x-listing-hero
    title="پرورش‌دهندگان"
    lede="جستجو در نام، شهر و نژاد — فیلتر تایپی برای پیدا کردن پرورش‌دهنده مناسب."
    icon="dogs"
    image="images/demo/hero/hero-02.jpg"
    :crumbs="[
        ['label' => 'خانه', 'route' => route('frontend.home')],
        ['label' => 'پرورش‌دهندگان'],
    ]"
/>

<div class="wrap sp-listing" style="padding-top:28px;padding-bottom:56px">
    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form :filters="$filters" />
        </div>
        <div class="sp-listing__results">
            <div class="grid grid-3">
                @forelse($breeders as $breeder)
                    <div class="sp-listing-card">
                        <x-breeder-card :item="$breeder" />
                    </div>
                @empty
                    <div class="panel" style="grid-column:1/-1;text-align:center">
                        <p style="margin:0;color:var(--sp-text-muted)">پرورش‌دهنده‌ای یافت نشد.</p>
                    </div>
                @endforelse
            </div>
            @if(method_exists($breeders, 'hasPages') && $breeders->hasPages())
                <nav class="sp-listing__pager" aria-label="صفحه‌بندی">
                    <div class="sp-pagination">{{ $breeders->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
                </nav>
            @endif
        </div>
    </div>
</div>

<x-scripts.filter-submit />
@endsection