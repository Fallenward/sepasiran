@extends('layouts.app')

@section('title', 'مربیان')

@section('content')
<x-listing-hero
    title="مربیان"
    lede="جستجو در نام، شهر و تخصص — رزرو مربی با فیلتر تایپی."
    icon="coach"
    image="images/demo/hero/hero-03.jpg"
    :crumbs="[
        ['label' => 'خانه', 'route' => route('frontend.home')],
        ['label' => 'مربیان'],
    ]"
/>

<div class="wrap sp-listing" style="padding-top:28px;padding-bottom:56px">
    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form :filters="$filters" />
        </div>
        <div class="sp-listing__results">
            <div class="grid grid-3">
                @forelse($coaches as $coach)
                    <div class="sp-listing-card">
                        <x-coach-card :item="$coach" />
                    </div>
                @empty
                    <div class="panel" style="grid-column:1/-1;text-align:center">
                        <p style="margin:0;color:var(--sp-text-muted)">مربی‌ای یافت نشد.</p>
                    </div>
                @endforelse
            </div>
            @if(method_exists($coaches, 'hasPages') && $coaches->hasPages())
                <nav class="sp-listing__pager" aria-label="صفحه‌بندی">
                    <div class="sp-pagination">{{ $coaches->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
                </nav>
            @endif
        </div>
    </div>
</div>

<x-scripts.filter-submit />
@endsection