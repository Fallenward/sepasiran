@extends('frontend.layouts.app')

@section('title', 'وبلاگ')

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
        <p class="latin" style="margin:0 0 6px;opacity:.7">Blog</p>
        <h1 style="margin:0 0 8px;font-size:clamp(1.8rem,3vw,2.4rem)">وبلاگ سپاسیران</h1>
        <p style="margin:0;max-width:40rem;line-height:1.8;opacity:.85">راهنماها، نکات نگهداری و مقاله‌های کاربردی درباره سگ و خدمات مرتبط.</p>
        <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap">
            <a class="sp-btn sp-btn--primary" href="{{ route('frontend.blogs.index') }}">همه مقالات</a>
            <a class="sp-btn sp-btn--ghost" href="{{ route('frontend.news.index') }}">اخبار</a>
        </div>
    </header>

    @if($mostReviewedBlogs->isNotEmpty())
        <div style="margin-bottom:36px">
            <h2 style="margin:0 0 14px;font-size:1.2rem">پربیننده‌ترین‌ها</h2>
            <div style="display:grid;gap:16px;grid-template-columns:repeat(auto-fill,minmax(240px,1fr))">
                @foreach($mostReviewedBlogs as $blog)
                    <a href="{{ route('frontend.blogs.show', $blog->slug) }}" style="display:block;text-decoration:none;color:inherit;border:1px solid rgba(255,255,255,.08);border-radius:14px;overflow:hidden;background:rgba(255,255,255,.03)">
                        <img src="{{ $imageUrl($blog) }}" alt="{{ $blog->title }}" style="width:100%;height:160px;object-fit:cover;display:block">
                        <div style="padding:14px">
                            <h3 style="margin:0 0 8px;font-size:1rem;line-height:1.6">{{ \Illuminate\Support\Str::limit($blog->title, 70) }}</h3>
                            <p style="margin:0;opacity:.75;font-size:.9rem;line-height:1.7">{{ \Illuminate\Support\Str::limit(strip_tags((string) $blog->content), 110) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div>
        <h2 style="margin:0 0 14px;font-size:1.2rem">تازه‌ترین مقالات</h2>
        <div style="display:grid;gap:16px;grid-template-columns:repeat(auto-fill,minmax(240px,1fr))">
            @forelse($latestBlogs as $blog)
                <a href="{{ route('frontend.blogs.show', $blog->slug) }}" style="display:block;text-decoration:none;color:inherit;border:1px solid rgba(255,255,255,.08);border-radius:14px;overflow:hidden;background:rgba(255,255,255,.03)">
                    <img src="{{ $imageUrl($blog) }}" alt="{{ $blog->title }}" style="width:100%;height:160px;object-fit:cover;display:block">
                    <div style="padding:14px">
                        <h3 style="margin:0 0 8px;font-size:1rem;line-height:1.6">{{ \Illuminate\Support\Str::limit($blog->title, 70) }}</h3>
                        <p style="margin:0;opacity:.75;font-size:.9rem;line-height:1.7">{{ \Illuminate\Support\Str::limit(strip_tags((string) $blog->content), 110) }}</p>
                    </div>
                </a>
            @empty
                <p style="opacity:.7">مقاله‌ای برای نمایش نیست.</p>
            @endforelse
        </div>
        <div style="margin-top:24px">{{ $latestBlogs->withQueryString()->links() }}</div>
    </div>
</section>
@endsection