@extends('layouts.app')

@section('title', 'وبلاگ')

@section('content')
<section class="sp-listing-hero">
    <div class="sp-listing-hero__bg" aria-hidden="true">
        <img src="{{ asset('images/demo/blogs/blog-02.jpg') }}" alt="">
    </div>
    <div class="wrap sp-listing-hero__inner">
        <p class="sp-editorial__brand latin">Sepasiran</p>
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>وبلاگ</span>
        </nav>
        <div class="sp-listing-hero__title-row">
            <h1>وبلاگ سپاسیران</h1>
        </div>
        <p class="sp-listing-hero__lede">راهنماها و مقاله‌های کاربردی برای نگهداری، آموزش و زندگی بهتر با سگ.</p>
        <div class="sp-editorial__actions">
            <a class="sp-btn sp-btn--primary" href="{{ route('frontend.blogs.index') }}">مقاله‌ها</a>
            <a class="sp-btn sp-btn--ghost" href="{{ route('frontend.news.index') }}">اخبار</a>
        </div>
    </div>
</section>

<section class="section sp-editorial">
    <div class="wrap">
        @if($mostReviewedBlogs->isNotEmpty())
            <h2 class="sp-story-section-title">پربیننده‌ترین‌ها</h2>
            <div class="sp-editorial__grid" style="margin-bottom:34px">
                @foreach($mostReviewedBlogs as $blog)
                    <a class="sp-story-tile" href="{{ route('frontend.blogs.show', $blog->slug) }}">
                        <div class="sp-story-tile__media" aria-hidden="true">
                            <img src="{{ $blog->imageUrl() }}" alt="">
                        </div>
                        <div class="sp-story-tile__body">
                            <p class="sp-story-tile__kicker">مقاله · {{ number_format((int) ($blog->views ?? 0)) }} بازدید</p>
                            <h3>{{ \Illuminate\Support\Str::limit($blog->title, 72) }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) ($blog->description ?: $blog->content)), 110) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        <h2 class="sp-story-section-title">تازه‌ترین مقالات</h2>
        <div class="sp-editorial__grid">
            @forelse($latestBlogs as $blog)
                <a class="sp-story-tile" href="{{ route('frontend.blogs.show', $blog->slug) }}">
                    <div class="sp-story-tile__media" aria-hidden="true">
                        <img src="{{ $blog->imageUrl() }}" alt="">
                    </div>
                    <div class="sp-story-tile__body">
                        <p class="sp-story-tile__kicker">
                            @if($blog->created_at)
                                {{ optional($blog->created_at)->timezone(config('app.timezone'))->format('Y/m/d') }}
                            @else
                                مقاله
                            @endif
                        </p>
                        <h3>{{ \Illuminate\Support\Str::limit($blog->title, 72) }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) ($blog->description ?: $blog->content)), 110) }}</p>
                    </div>
                </a>
            @empty
                <p style="color:var(--sp-text-muted)">مقاله‌ای برای نمایش نیست.</p>
            @endforelse
        </div>
        <div style="margin-top:28px">{{ $latestBlogs->withQueryString()->links() }}</div>
    </div>
</section>
@endsection