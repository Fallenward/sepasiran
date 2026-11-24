@extends('frontend.layouts.app')

@section('title', 'اخبار')

@section('content')
@php
    $imageUrl = function ($item) {
        $image = $item->image ?? null;
        if (! $image) {
            return asset('images/placeholder.jpg');
        }
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }
        if (str_starts_with($image, 'images/') || str_starts_with($image, 'storage/')) {
            return asset($image);
        }

        return asset('storage/'.$image);
    };
@endphp

<section class="wrap" style="padding:32px 0 56px">
    <header style="margin-bottom:28px">
        <p class="latin" style="margin:0 0 6px;opacity:.7">News</p>
        <h1 style="margin:0 0 8px;font-size:clamp(1.8rem,3vw,2.4rem)">اخبار سپاسیران</h1>
        <p style="margin:0;max-width:40rem;line-height:1.8;opacity:.85">تازه‌ترین خبرها و اطلاع‌رسانی‌های مربوط به خدمات، رویدادها و جامعه سپاسیران.</p>
        <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap">
            <a class="sp-btn sp-btn--primary" href="{{ route('frontend.news.index') }}">همه اخبار</a>
            <a class="sp-btn sp-btn--ghost" href="{{ route('frontend.blogs.index') }}">وبلاگ</a>
        </div>
    </header>

    @if($latestNews)
        <a href="{{ route('frontend.blogs.show', $latestNews->slug) }}" style="display:grid;gap:18px;grid-template-columns:1.1fr .9fr;align-items:center;text-decoration:none;color:inherit;margin-bottom:36px;border:1px solid rgba(255,255,255,.08);border-radius:16px;overflow:hidden;background:rgba(255,255,255,.03)">
            <div style="padding:22px">
                <p style="margin:0 0 8px;opacity:.7;font-size:.85rem">خبر ویژه</p>
                <h2 style="margin:0 0 10px;font-size:clamp(1.3rem,2.4vw,1.8rem);line-height:1.6">{{ $latestNews->title }}</h2>
                <p style="margin:0;opacity:.8;line-height:1.8">{{ \Illuminate\Support\Str::limit(strip_tags((string) ($latestNews->description ?? $latestNews->content)), 180) }}</p>
            </div>
            <img src="{{ $imageUrl($latestNews) }}" alt="{{ $latestNews->title }}" style="width:100%;height:100%;min-height:220px;object-fit:cover;display:block">
        </a>
        <style>
            @media (max-width: 800px) {
                section.wrap > a[href*="/blogs/"] { grid-template-columns: 1fr !important; }
            }
        </style>
    @endif

    <h2 style="margin:0 0 14px;font-size:1.2rem">سایر اخبار</h2>
    <div style="display:grid;gap:16px;grid-template-columns:repeat(auto-fill,minmax(240px,1fr))">
        @forelse($newsItems as $news)
            <a href="{{ route('frontend.blogs.show', $news->slug) }}" style="display:block;text-decoration:none;color:inherit;border:1px solid rgba(255,255,255,.08);border-radius:14px;overflow:hidden;background:rgba(255,255,255,.03)">
                <img src="{{ $imageUrl($news) }}" alt="{{ $news->title }}" style="width:100%;height:160px;object-fit:cover;display:block">
                <div style="padding:14px">
                    <h3 style="margin:0 0 8px;font-size:1rem;line-height:1.6">{{ \Illuminate\Support\Str::limit($news->title, 70) }}</h3>
                    <p style="margin:0;opacity:.75;font-size:.9rem;line-height:1.7">{{ \Illuminate\Support\Str::limit(strip_tags((string) $news->content), 110) }}</p>
                </div>
            </a>
        @empty
            <p style="opacity:.7">خبری برای نمایش نیست.</p>
        @endforelse
    </div>
    <div style="margin-top:24px">{{ $newsItems->withQueryString()->links() }}</div>
</section>
@endsection