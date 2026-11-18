@extends('layouts.app')

@section('title', 'کلینیک‌ها')

@section('content')
<x-listing-hero
    title="کلینیک‌ها"
    lede="جستجو در نام، شهر و تخصص — کلینیک مناسب را سریع پیدا کنید."
    icon="clinic"
    image="images/demo/blogs/blog-04.jpg"
    :crumbs="[
        ['label' => 'خانه', 'route' => route('frontend.home')],
        ['label' => 'کلینیک‌ها'],
    ]"
/>

<div class="wrap sp-listing" style="padding-top:28px;padding-bottom:56px">
    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form :filters="$filters" />
        </div>
        <div class="sp-listing__results">
            <div class="grid grid-3">
                @forelse($clinics as $clinic)
                    <div class="sp-listing-card">
                        <x-clinic-card :item="$clinic" />
                    </div>
                @empty
                    <div class="panel" style="grid-column:1/-1;text-align:center">
                        <p style="margin:0;color:var(--sp-text-muted)">کلینیکی یافت نشد.</p>
                    </div>
                @endforelse
            </div>
            @if(method_exists($clinics, 'hasPages') && $clinics->hasPages())
                <nav class="sp-listing__pager" aria-label="صفحه‌بندی">
                    <div class="sp-pagination">{{ $clinics->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
                </nav>
            @endif
        </div>
    </div>
</div>

<x-scripts.filter-submit />
@endsection