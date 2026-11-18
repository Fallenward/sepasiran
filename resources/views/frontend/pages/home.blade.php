@extends('layouts.app')

@section('title', $title)

@php
    $heroImage = null;
    if (isset($sliders) && $sliders->isNotEmpty() && !empty($sliders->first()->image)) {
        $img = ltrim($sliders->first()->image, '/');
        $heroImage = str_starts_with($img, 'images/') ? asset($img) : asset('storage/' . $img);
    }
    $aboutText = optional($settings ?? null)->about_setting_about
        ?? optional($settings ?? null)->footer_setting_about
        ?? 'سپاسیران بازار مینیمال سگ، مربی، کلینیک و فروشگاه است — با تمرکز روی اعتماد، وضوح و تجربهٔ فارسی.';
    $aboutImage = optional($settings ?? null)->about_setting_img;
    if ($aboutImage && !str_starts_with($aboutImage, 'http')) {
        $aboutImage = asset($aboutImage);
    }
    if (!$aboutImage) {
        $aboutImage = asset('images/demo/hero/hero-02.jpg');
    }
@endphp

@section('content')
    @php
        $heroProps = [
            'brand' => 'سپاسیران',
            'title' => 'همه‌چیز برای همراه چهارپای شما',
            'lede' => 'بازار سگ، مربی، کلینیک و فروشگاه — مینیمال، مطمئن، فارسی.',
            'dogsUrl' => route('frontend.dog.supply.index'),
            'servicesUrl' => route('frontend.services.index'),
            'heroImage' => $heroImage,
        ];
    @endphp
    <div data-vue="home-hero" data-props='@json($heroProps)'></div>

    <section class="section">
        <div class="wrap">
            <div class="section__head">
                <h2>از کجا شروع می‌کنید؟</h2>
                <p>یک مسیر را انتخاب کنید — بدون شلوغی کارت و بنر.</p>
            </div>
            <div class="paths">
                <a class="path" href="{{ route('frontend.dog.supply.index') }}">
                    <div>
                        <h3>سگ‌ها</h3>
                        <p>لیست نژاد، شهر و مشخصات</p>
                    </div>
                    <div class="path__mark" aria-hidden="true">◆</div>
                </a>
                <a class="path" href="{{ route('frontend.services.index') }}">
                    <div>
                        <h3>خدمات</h3>
                        <p>مربی، کلینیک، بیمه</p>
                    </div>
                    <div class="path__mark" aria-hidden="true">✦</div>
                </a>
                <a class="path" href="{{ route('frontend.stores.index') }}">
                    <div>
                        <h3>فروشگاه</h3>
                        <p>غذا، لوازم، مراقبت</p>
                    </div>
                    <div class="path__mark" aria-hidden="true">◇</div>
                </a>
            </div>
        </div>
    </section>

    @if(isset($featuredDogs) && $featuredDogs->isNotEmpty())
        <section class="section" style="padding-top:0">
            <div class="wrap">
                <div class="section__head sp-section-head-row">
                    <div>
                        <h2>سگ‌های منتخب</h2>
                        <p>جدیدترین آگهی‌ها برای شروع جستجو.</p>
                    </div>
                    <a class="btn btn-ghost" href="{{ route('frontend.dog.supply.index') }}">مشاهده همه</a>
                </div>
                <div class="sp-snap-row">
                    @foreach($featuredDogs->take(6) as $dog)
                        <div class="sp-snap-row__item">
                            @include('components.dog-card', ['item' => $dog])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section" style="padding-top:0">
        <div class="wrap">
            <div class="section__head">
                <h2>خدمات</h2>
                <p>مربی، کلینیک، بیمه و دوره — متن‌محور و سریع.</p>
            </div>
            <div class="sp-service-lanes">
                @foreach(($servicePillars ?? []) as $pillar)
                    <a class="sp-service-lane" href="{{ $pillar['href'] }}">
                        <h3>{{ $pillar['title'] }}</h3>
                        <p>{{ $pillar['text'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section" style="padding-top:0">
        <div class="wrap">
            <div class="section__head">
                <h2>چطور کار می‌کند؟</h2>
                <p>سه قدم ساده تا انتخاب مطمئن.</p>
            </div>
            <ol class="sp-steps">
                <li class="sp-step">
                    <span class="sp-step__num num">۱</span>
                    <div>
                        <h3>مسیر را انتخاب کنید</h3>
                        <p>سگ، خدمت یا فروشگاه — فقط یک کار در هر لحظه.</p>
                    </div>
                </li>
                <li class="sp-step">
                    <span class="sp-step__num num">۲</span>
                    <div>
                        <h3>فیلتر و مقایسه</h3>
                        <p>نژاد، شهر و تخصص را شفاف ببینید و کوتاه‌لیست کنید.</p>
                    </div>
                </li>
                <li class="sp-step">
                    <span class="sp-step__num num">۳</span>
                    <div>
                        <h3>ارتباط و اقدام</h3>
                        <p>رزرو، خرید یا گفتگو — بدون شلوغی فرم‌های اداری.</p>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    @if(isset($featuredProducts) && $featuredProducts->isNotEmpty())
        <section class="section" style="padding-top:0">
            <div class="wrap">
                <div class="section__head sp-section-head-row">
                    <div>
                        <h2>از فروشگاه</h2>
                        <p>لوازم و مراقبت برای همراه شما.</p>
                    </div>
                    <a class="btn btn-ghost" href="{{ route('frontend.stores.index') }}">ورود به فروشگاه</a>
                </div>
                <div class="grid grid-4">
                    @foreach($featuredProducts->take(4) as $product)
                        <x-product-card :item="$product" :showRoute="'frontend.products.show'" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section" style="padding-top:0">
        <div class="wrap">
            <div class="sp-trust">
                <div class="sp-trust__media">
                    <img src="{{ $aboutImage }}" alt="سپاسیران">
                </div>
                <div class="sp-trust__body">
                    <p class="sp-trust__eyebrow">اعتماد و همراهی</p>
                    <h2>بازار آرام برای حیوان خانگی</h2>
                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($aboutText), 220) }}</p>
                    <div class="sp-trust__actions">
                        <a class="btn btn-primary" href="{{ route('frontend.about') }}">درباره سپاسیران</a>
                        <a class="btn btn-ghost" href="{{ route('frontend.shelter.index') }}">پناهگاه</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(isset($statistics) && count($statistics))
        <section class="section" style="padding-top:0">
            <div class="wrap">
                <div class="sp-stat-row" aria-label="آمار کلی">
                    @foreach($statistics as $stat)
                        <div class="sp-stat">
                            <strong class="num">{{ number_format((int) $stat['counter']) }}</strong>
                            <span>{{ $stat['title'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(isset($blogs) && $blogs->isNotEmpty())
        <section class="section" style="padding-top:0">
            <div class="wrap">
                <div class="section__head sp-section-head-row">
                    <div>
                        <h2>نکته‌ها و اخبار</h2>
                        <p>مطالب کوتاه برای مراقبت بهتر.</p>
                    </div>
                    <a class="btn btn-ghost" href="{{ route('frontend.news.index') }}">مشاهده بیشتر</a>
                </div>
                <div class="grid grid-3">
                    @foreach($blogs as $blog)
                        @php
                            $blogImgPath = ltrim($blog->image ?? 'images/demo/blogs/blog-01.jpg', '/');
                            $blogImg = str_starts_with($blogImgPath, 'images/') ? asset($blogImgPath) : asset('storage/' . $blogImgPath);
                        @endphp
                        <article class="sp-card sp-card--blog">
                            <a class="sp-card__link" href="{{ route('frontend.blogs.show', $blog->slug) }}">
                                <div class="sp-card__media">
                                    <img src="{{ $blogImg }}" alt="{{ $blog->title }}" loading="lazy">
                                </div>
                                <div class="sp-card__body">
                                    <h3 class="sp-card__title">{{ \Illuminate\Support\Str::limit($blog->title, 52) }}</h3>
                                    <p class="sp-card__meta">{{ \Illuminate\Support\Str::limit(strip_tags($blog->description ?? $blog->content ?? ''), 70) }}</p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section" style="padding-top:0">
        <div class="wrap">
            <div class="sp-final-cta">
                <div>
                    <h2>آماده‌اید شروع کنید؟</h2>
                    <p>از سگ‌های منتخب ببینید یا همین حالا خدمت رزرو کنید.</p>
                </div>
                <div class="sp-final-cta__actions">
                    <a class="btn btn-primary" href="{{ route('frontend.dog.supply.index') }}">مشاهده سگ‌ها</a>
                    <a class="btn btn-ghost" href="{{ route('frontend.coaches.index') }}">رزرو مربی</a>
                </div>
            </div>
        </div>
    </section>
@endsection