@extends('layouts.app')

@section('title', 'اخبار')

@section('content')
<section class="sp-listing-hero">
    <div class="sp-listing-hero__bg" aria-hidden="true">
        <img src="{{ asset('images/demo/hero/hero-03.jpg') }}" alt="">
    </div>
    <div class="wrap sp-listing-hero__inner">
        <p class="sp-editorial__brand latin">Sepasiran</p>
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>اخبار</span>
        </nav>
        <div class="sp-listing-hero__title-row">
            <h1>اخبار سپاسیران</h1>
        </div>
        <p class="sp-listing-hero__lede">تازه‌ترین خبرها و اطلاع‌رسانی‌های خدمات، رویدادها و جامعه سپاسیران.</p>
        <div class="sp-editorial__actions">
            <a class="sp-btn sp-btn--primary" href="{{ route('frontend.news.index') }}">اخبار</a>
            <a class="sp-btn sp-btn--ghost" href="{{ route('frontend.blogs.index') }}">وبلاگ</a>
        </div>
    </div>
</section>

<section class="section sp-editorial">
    <div class="wrap">
        @if($latestNews)
            <a class="sp-story-feature" href="{{ route('frontend.blogs.show', $latestNews->slug) }}">
                <div class="sp-story-feature__copy">
                    <p class="sp-story-tile__kicker">خبر ویژه</p>
                    <h2>{{ $latestNews->title }}</h2>
                    <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) ($latestNews->description ?: $latestNews->content)), 200) }}</p>
                    <span class="sp-btn sp-btn--ghost" style="align-self:flex-start;pointer-events:none">ادامه مطلب</span>
                </div>
                <div class="sp-story-feature__media" aria-hidden="true">
                    <img src="{{ $latestNews->imageUrl() }}" alt="">
                </div>
            </a>
        @endif

        <h2 class="sp-story-section-title">سایر اخبار</h2>
        <div class="sp-editorial__grid">
            @forelse($newsItems as $news)
                <a class="sp-story-tile" href="{{ route('frontend.blogs.show', $news->slug) }}">
                    <div class="sp-story-tile__media" aria-hidden="true">
                        <img src="{{ $news->imageUrl() }}" alt="">
                    </div>
                    <div class="sp-story-tile__body">
                        <p class="sp-story-tile__kicker">
                            {{ optional($news->created_at)->timezone(config('app.timezone'))->format('Y/m/d') ?: 'خبر' }}
                        </p>
                        <h3>{{ \Illuminate\Support\Str::limit($news->title, 72) }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) ($news->description ?: $news->content)), 110) }}</p>
                    </div>
                </a>
            @empty
                <p style="color:var(--sp-text-muted)">خبری برای نمایش نیست.</p>
            @endforelse
        </div>
        <div style="margin-top:28px">{{ $newsItems->withQueryString()->links() }}</div>
    </div>
</section>
@endsection