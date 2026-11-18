@extends('layouts.app')

@section('title', 'بیمه حیوانات')

@section('content')
<x-listing-hero
    title="بیمه حیوانات"
    lede="جستجو در نام و شهر — پوشش مناسب برای همراه شما."
    icon="insurance"
    image="images/demo/hero/hero-03.jpg"
    :crumbs="[
        ['label' => 'خانه', 'route' => route('frontend.home')],
        ['label' => 'بیمه حیوانات'],
    ]"
/>

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
                        <p style="margin:0;color:var(--sp-text-muted)">بیمه‌ای یافت نشد.</p>
                    </div>
                @endforelse
            </div>
            @if(method_exists($insurances, 'hasPages') && $insurances->hasPages())
                <nav class="sp-listing__pager" aria-label="صفحه‌بندی">
                    <div class="sp-pagination">{{ $insurances->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
                </nav>
            @endif
        </div>
    </div>
</div>

<x-scripts.filter-submit />
@endsection