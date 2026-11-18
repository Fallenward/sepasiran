@extends('layouts.app')

@section('title', 'فروشگاه')

@section('content')
<x-listing-hero
    title="فروشگاه"
    lede="جستجو در نام محصول — غذا، لوازم و مراقبت برای همراه شما."
    icon="store"
    image="images/demo/products/product-01.jpg"
    :crumbs="[
        ['label' => 'خانه', 'route' => route('frontend.home')],
        ['label' => 'فروشگاه'],
    ]"
/>

<div class="wrap sp-listing" style="padding-top:28px;padding-bottom:56px">
    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form :filters="$filters" />
        </div>
        <div class="sp-listing__results">
            <div class="grid grid-3">
                @forelse($products as $product)
                    <div class="sp-listing-card">
                        <x-product-card :item="$product" :showRoute="'frontend.products.show'" />
                    </div>
                @empty
                    <div class="panel" style="grid-column:1/-1;text-align:center">
                        <p style="margin:0;color:var(--sp-text-muted)">محصولی یافت نشد.</p>
                    </div>
                @endforelse
            </div>
            @if(method_exists($products, 'hasPages') && $products->hasPages())
                <nav class="sp-listing__pager" aria-label="صفحه‌بندی">
                    <div class="sp-pagination">{{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
                </nav>
            @endif
        </div>
    </div>
</div>

<x-scripts.filter-submit />
@endsection