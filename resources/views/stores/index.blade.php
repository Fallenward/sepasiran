@extends('layouts.app')

@section('title', 'فروشگاه')

@section('content')
<div class="wrap sp-listing" style="padding-top:24px;padding-bottom:48px">
    <header class="sp-listing__head">
        <div>
            <h1 class="sp-page-title">فروشگاه</h1>
            <p class="sp-count">غذا، لوازم و مراقبت برای همراه شما</p>
        </div>
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>فروشگاه</span>
        </nav>
    </header>

    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form :filters="$filters" />
        </div>

        <div class="sp-listing__results">
            <div class="grid grid-3">
                @forelse($products as $product)
                    <x-product-card :item="$product" :showRoute="'frontend.products.show'" />
                @empty
                    <div class="panel" style="grid-column:1/-1;text-align:center">
                        <p style="margin:0;color:var(--sp-text-muted)">محصولی یافت نشد.</p>
                    </div>
                @endforelse
            </div>

            @if(isset($blogs) && $blogs->isNotEmpty())
                <section class="section" style="padding-bottom:0">
                    <div class="section__head">
                        <h2>بلاگ‌ها</h2>
                        <p>نکته‌های کوتاه مراقبت و نگهداری.</p>
                    </div>
                    <div class="grid grid-3">
                        @foreach($blogs as $blog)
                            <article class="tile">
                                <a class="tile-link" href="{{ route('frontend.blogs.show', $blog->slug) }}">
                                    <div class="tile__media tile__media--square">
                                        <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('images/blog-placeholder.jpg') }}"
                                             alt="{{ $blog->title }}">
                                    </div>
                                    <div class="tile__body">
                                        <h3>{{ \Illuminate\Support\Str::limit($blog->title, 50) }}</h3>
                                        <div class="tile__meta">
                                            <span>{{ \Illuminate\Support\Str::limit($blog->description ?? $blog->content ?? '', 70) }}</span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</div>

<x-scripts.filter-submit />
@endsection