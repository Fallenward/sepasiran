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
    if ($aboutImage && str_starts_with($aboutImage, 'http')) {
        $path = parse_url($aboutImage, PHP_URL_PATH) ?: '';
        $aboutImage = ltrim($path, '/');
    }
    if ($aboutImage && !str_starts_with($aboutImage, 'http')) {
        $localPath = public_path(ltrim($aboutImage, '/'));
        $aboutImage = file_exists($localPath) ? asset(ltrim($aboutImage, '/')) : null;
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
            'servicesUrl' => route('frontend.home').'#services',
            'bookingUrl' => route('frontend.booking.index'),
            'heroImage' => $heroImage,
        ];
    @endphp
    <div data-vue="home-hero" data-props='@json($heroProps)'></div>

    <section class="section">
        <div class="wrap">
            <x-sp-section-head
                title="از کجا شروع می‌کنید؟"
                subtitle="یک مسیر را انتخاب کنید — بدون شلوغی کارت و بنر."
                icon="start"
            />
            <div class="paths">
                <a class="path" href="{{ route('frontend.dog.supply.index') }}">
                    <span class="path__icon"><x-sp-icon name="dogs" /></span>
                    <div>
                        <h3>سگ‌ها</h3>
                        <p>لیست نژاد، شهر و مشخصات</p>
                    </div>
                </a>
                <a class="path" href="{{ route('frontend.home') }}#services">
                    <span class="path__icon"><x-sp-icon name="services" /></span>
                    <div>
                        <h3>خدمات</h3>
                        <p>مربی، کلینیک، بیمه</p>
                    </div>
                </a>
                <a class="path" href="{{ route('frontend.stores.index') }}">
                    <span class="path__icon"><x-sp-icon name="store" /></span>
                    <div>
                        <h3>فروشگاه</h3>
                        <p>غذا، لوازم، مراقبت</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    @if(isset($featuredDogs) && $featuredDogs->isNotEmpty())
        <section class="section" style="padding-top:0">
            <div class="wrap">
                <x-sp-section-head
                    title="سگ‌های منتخب"
                    subtitle="جدیدترین آگهی‌ها برای شروع جستجو."
                    icon="dogs"
                    :action-href="route('frontend.dog.supply.index')"
                    action-label="مشاهده همه"
                />
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

    <section id="services" class="section" style="padding-top:0">
        <div class="wrap">
            <x-sp-section-head
                title="خدمات"
                subtitle="مربی، کلینیک، فروشگاه و دوره — فشرده و سریع."
                icon="services"
            />
            <div class="sp-service-lanes sp-service-lanes--compact">
                @forelse(($services ?? collect()) as $service)
                    @php
                        $serviceIcon = match ($service->slug) {
                            'antkhab-mrby' => 'coach',
                            'thyh-sg' => 'dogs',
                            'dorh-hay-anlayn' => 'course',
                            'froshgah-ha' => 'store',
                            'klynyk-ha-o-bymh-hyoanat' => 'clinic',
                            'prorsh-dhndh' => 'dogs',
                            default => 'services',
                        };
                    @endphp
                    <a class="sp-service-lane" href="{{ $service->route_link }}">
                        <span class="sp-service-lane__icon">
                            <x-sp-icon :name="$serviceIcon" />
                        </span>
                        <h3>{{ $service->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($service->description ?? ''), 48) }}</p>
                    </a>
                @empty
                    @foreach(($servicePillars ?? []) as $pillar)
                        <a class="sp-service-lane" href="{{ $pillar['href'] }}">
                            <span class="sp-service-lane__icon">
                                <x-sp-icon :name="$pillar['icon'] ?? 'spark'" />
                            </span>
                            <h3>{{ $pillar['title'] }}</h3>
                            <p>{{ $pillar['text'] }}</p>
                        </a>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <section class="section" style="padding-top:0">
        <div class="wrap">
            <x-sp-section-head
                title="چطور کار می‌کند؟"
                subtitle="سه قدم ساده تا انتخاب مطمئن."
                icon="steps"
            />
            <ol class="sp-steps">
                <li class="sp-step">
                    <span class="sp-step__icon"><x-sp-icon name="start" /></span>
                    <div>
                        <h3>مسیر را انتخاب کنید</h3>
                        <p>سگ، خدمت یا فروشگاه — فقط یک کار در هر لحظه.</p>
                    </div>
                </li>
                <li class="sp-step">
                    <span class="sp-step__icon"><x-sp-icon name="dogs" /></span>
                    <div>
                        <h3>فیلتر و مقایسه</h3>
                        <p>نژاد، شهر و تخصص را شفاف ببینید و کوتاه‌لیست کنید.</p>
                    </div>
                </li>
                <li class="sp-step">
                    <span class="sp-step__icon"><x-sp-icon name="cta" /></span>
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
                <x-sp-section-head
                    title="از فروشگاه"
                    subtitle="لوازم و مراقبت برای همراه شما."
                    icon="store"
                    :action-href="route('frontend.stores.index')"
                    action-label="ورود به فروشگاه"
                />
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
                    <img src="{{ $aboutImage }}" alt="سپاسیران" loading="lazy">
                </div>
                <div class="sp-trust__body">
                    <p class="sp-trust__eyebrow">
                        <span class="sp-section-head__icon sp-section-head__icon--inline" aria-hidden="true"><x-sp-icon name="trust" /></span>
                        اعتماد و همراهی
                    </p>
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
                            <span class="sp-stat__icon"><x-sp-icon :name="$stat['icon'] ?? 'spark'" /></span>
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
                <x-sp-section-head
                    title="نکته‌ها و اخبار"
                    subtitle="مطالب کوتاه برای مراقبت بهتر."
                    icon="news"
                    :action-href="route('frontend.news.index')"
                    action-label="مشاهده بیشتر"
                />
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
                    <div class="sp-section-head__title-row" style="margin-bottom:8px">
                        <span class="sp-section-head__icon" aria-hidden="true"><x-sp-icon name="cta" /></span>
                        <h2 style="margin:0">آماده‌اید شروع کنید؟</h2>
                    </div>
                    <p>از سگ‌های منتخب ببینید یا همین حالا خدمت رزرو کنید.</p>
                </div>
                <div class="sp-final-cta__actions">
                    <a class="btn btn-primary" href="{{ route('frontend.dog.supply.index') }}">مشاهده سگ‌ها</a>
                    <a class="btn btn-ghost" href="{{ route('frontend.booking.index', ['type' => 'coach']) }}">رزرو مربی</a>
                </div>
            </div>
        </div>
    </section>
@endsection