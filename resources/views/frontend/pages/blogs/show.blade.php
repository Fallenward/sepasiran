@extends('layouts.app')

@section('title', $title ?? ($blog->title ?? 'مطلب'))

@section('content')
@php
    $isNews = $blog->isNews();
    $listRoute = $isNews ? route('frontend.news.index') : route('frontend.blogs.index');
    $listLabel = $isNews ? 'اخبار' : 'وبلاگ';
    $dateLabel = optional($blog->created_at)->timezone(config('app.timezone'))->format('Y/m/d');
    $ratingValue = (float) ($blog->rating ?? $blog->average_rating ?? 0);
    $fullStars = (int) floor($ratingValue);
    $halfStar = ($ratingValue - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = max(0, 5 - $fullStars - $halfStar);
@endphp

<article class="sp-editorial">
    <header class="sp-article-hero">
        <div class="sp-article-hero__bg" aria-hidden="true">
            <img src="{{ $blog->imageUrl() }}" alt="">
        </div>
        <div class="wrap sp-article-hero__inner">
            <p class="sp-editorial__brand latin">Sepasiran</p>
            <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
                <a href="{{ route('frontend.home') }}">خانه</a>
                <span class="sp-breadcrumb__sep">/</span>
                <a href="{{ $listRoute }}">{{ $listLabel }}</a>
                <span class="sp-breadcrumb__sep">/</span>
                <span>{{ \Illuminate\Support\Str::limit($blog->title, 40) }}</span>
            </nav>
            <h1>{{ $blog->title }}</h1>
            <div class="sp-article-hero__meta">
                <span>{{ $isNews ? 'خبر' : 'مقاله' }}</span>
                @if($dateLabel)
                    <span class="num">{{ $dateLabel }}</span>
                @endif
                @if(!empty($blog->views))
                    <span class="num">{{ number_format((int) $blog->views) }} بازدید</span>
                @endif
                @if(optional($blog->category)->name)
                    <span>{{ $blog->category->name }}</span>
                @endif
            </div>
        </div>
    </header>

    <div class="wrap sp-article-layout">
        <div>
            <div class="sp-provider__panel">
                <div class="sp-article-prose">
                    {!! $blog->content !!}
                </div>

                @if($ratingValue > 0 || ($blogRatingCount ?? 0) > 0)
                    <div class="sp-article-rating" aria-label="امتیاز">
                        @for($i = 0; $i < $fullStars; $i++)
                            <i class="fa fa-star" aria-hidden="true"></i>
                        @endfor
                        @if($halfStar)
                            <i class="fa fa-star-half" aria-hidden="true"></i>
                        @endif
                        @for($i = 0; $i < $emptyStars; $i++)
                            <i class="fa fa-star" style="opacity:.35" aria-hidden="true"></i>
                        @endfor
                        <strong class="num rating-number">{{ number_format($ratingValue, 1) }}</strong>
                        <span>از {{ number_format((int) ($blogRatingCount ?? 0)) }} رای</span>
                    </div>
                @endif
            </div>

            <section class="sp-article-comments">
                <div class="sp-provider__panel">
                    <h2 style="margin:0 0 12px">نظرات</h2>
                    @php
                        $approved = ($blog->comments ?? collect())->where('status', \App\Models\Comment::STATUS_APPROVED);
                    @endphp
                    @forelse($approved as $comment)
                        <div class="sp-article-comment">
                            <div class="sp-article-comment__head">
                                <strong>{{ $comment->name ?: 'کاربر' }}</strong>
                                <span class="num">{{ optional($comment->created_at)->timezone(config('app.timezone'))->format('Y/m/d') }}</span>
                            </div>
                            <p>{{ $comment->content }}</p>
                        </div>
                    @empty
                        <p style="margin:0;color:var(--sp-text-muted)">هنوز نظری ثبت نشده. اولین نفر باشید.</p>
                    @endforelse
                </div>

                @if($blog->comments_enabled !== false)
                    <div class="sp-provider__panel">
                        <h2 style="margin:0 0 12px">ارسال نظر</h2>
                        @if(session('success'))
                            <p class="sp-provider__cta-note" style="margin-bottom:12px">{{ session('success') }}</p>
                        @endif
                        <form method="POST" action="{{ route('frontend.blogs.store.comment') }}" style="display:grid;gap:12px">
                            @csrf
                            <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                            @auth
                                <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                                <input type="hidden" name="family" value="{{ auth()->user()->family ?? '-' }}">
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                <input type="hidden" name="phone" value="{{ auth()->user()->phone ?: '00000000000' }}">
                            @else
                                <div style="display:grid;gap:12px;grid-template-columns:repeat(auto-fit,minmax(180px,1fr))">
                                    <div class="field" style="margin:0">
                                        <label for="name">نام</label>
                                        <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="field" style="margin:0">
                                        <label for="family">نام خانوادگی</label>
                                        <input id="family" type="text" name="family" value="{{ old('family') }}" required>
                                    </div>
                                    <div class="field" style="margin:0">
                                        <label for="email">ایمیل</label>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="field" style="margin:0">
                                        <label for="phone">موبایل</label>
                                        <input id="phone" type="text" name="phone" class="num" value="{{ old('phone') }}" required>
                                    </div>
                                </div>
                            @endauth
                            <div class="field" style="margin:0">
                                <label for="comment">متن نظر</label>
                                <textarea id="comment" name="comment" rows="4" required>{{ old('comment') }}</textarea>
                            </div>
                            <button type="submit" class="sp-btn sp-btn--primary" style="justify-self:start">ارسال نظر</button>
                        </form>
                    </div>
                @endif
            </section>
        </div>

        <aside class="sp-article-aside">
            <div class="sp-article-rail">
                <h2>مطالب مرتبط</h2>
                @forelse(($latestPosts ?? collect()) as $post)
                    <a class="sp-article-rail__item" href="{{ route('frontend.blogs.show', $post->slug) }}">
                        <img src="{{ $post->imageUrl() }}" alt="">
                        <div>
                            <strong>{{ \Illuminate\Support\Str::limit($post->title, 55) }}</strong>
                            <span class="num">{{ optional($post->created_at)->timezone(config('app.timezone'))->format('Y/m/d') }}</span>
                        </div>
                    </a>
                @empty
                    <p style="margin:0;color:var(--sp-text-muted)">مطلب مرتبطی نیست.</p>
                @endforelse
            </div>

            <div class="sp-provider__panel sp-provider__cta">
                <h2 style="margin:0">بیشتر بخوانید</h2>
                <p class="sp-provider__cta-note">به فهرست {{ $listLabel }} برگردید یا اخبار و مقالات تازه را ببینید.</p>
                <a class="sp-btn sp-btn--primary" style="width:100%;margin-top:10px" href="{{ $listRoute }}">همه {{ $listLabel }}</a>
                <a class="sp-btn sp-btn--ghost" style="width:100%;margin-top:8px" href="{{ $isNews ? route('frontend.blogs.index') : route('frontend.news.index') }}">
                    {{ $isNews ? 'وبلاگ' : 'اخبار' }}
                </a>
            </div>
        </aside>
    </div>
</article>
@endsection