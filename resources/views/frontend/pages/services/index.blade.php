@extends('layouts.app')

@section('title', 'خدمات')

@section('content')
<x-listing-hero
    title="خدمات"
    lede="جستجو بین مربی، کلینیک، فروشگاه، پرورش‌دهنده و دوره‌ها."
    icon="services"
    image="images/demo/hero/hero-02.jpg"
    :crumbs="[
        ['label' => 'خانه', 'route' => route('frontend.home')],
        ['label' => 'خدمات'],
    ]"
/>

<div class="wrap sp-listing" style="padding-top:28px;padding-bottom:56px">
    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form :filters="$filters" />
        </div>

        <div class="sp-listing__results">
            <div class="grid grid-3">
                @forelse($services as $service)
                    <div class="sp-listing-card">
                        <article class="sp-card sp-card--person">
                            <a class="sp-card__link" href="{{ $service->route_link }}">
                                <div class="sp-card__media">
                                    <img src="{{ $service->image_url }}" alt="{{ $service->title }}" loading="lazy">
                                </div>
                                <div class="sp-card__body">
                                    <h3 class="sp-card__title">{{ $service->title }}</h3>
                                    <p class="sp-card__meta">{{ \Illuminate\Support\Str::limit(strip_tags($service->description ?? ''), 80) }}</p>
                                </div>
                            </a>
                        </article>
                    </div>
                @empty
                    <div class="panel" style="grid-column:1/-1;text-align:center">
                        <p style="margin:0;color:var(--sp-text-muted)">خدمتی یافت نشد.</p>
                    </div>
                @endforelse
            </div>

            @if(method_exists($services, 'hasPages') && $services->hasPages())
                <nav class="sp-listing__pager" aria-label="صفحه‌بندی">
                    <div class="sp-pagination">
                        {{ $services->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </nav>
            @endif
        </div>
    </div>
</div>

<x-scripts.filter-submit />
@endsection