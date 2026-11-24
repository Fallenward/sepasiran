@extends('layouts.app')

@section('title', 'درباره سپاسیران')

@section('content')
@php
    $aboutText = optional($settings ?? null)->about_setting_about
        ?? optional($settings ?? null)->footer_setting_about
        ?? 'سپاسیران بازار مینیمال سگ، مربی، کلینیک و فروشگاه است — با تمرکز روی اعتماد، وضوح و تجربهٔ فارسی.';
    $aboutImage = optional($settings ?? null)->about_setting_img;
    if ($aboutImage && str_starts_with((string) $aboutImage, 'http')) {
        $path = parse_url($aboutImage, PHP_URL_PATH) ?: '';
        $aboutImage = ltrim($path, '/');
    }
    if ($aboutImage && ! str_starts_with((string) $aboutImage, 'http')) {
        $local = public_path(ltrim($aboutImage, '/'));
        $aboutImage = file_exists($local) ? asset(ltrim($aboutImage, '/')) : null;
    }
    if (! $aboutImage && isset($latestBlog) && ! empty($latestBlog->image)) {
        $raw = ltrim($latestBlog->image, '/');
        $aboutImage = str_starts_with($raw, 'images/') ? asset($raw) : asset('storage/'.$raw);
    }
    if (! $aboutImage) {
        $aboutImage = asset('images/demo/hero/hero-03.jpg');
    }
    $lede = \Illuminate\Support\Str::limit(strip_tags($latestBlog->excerpt ?? $latestBlog->content ?? $aboutText), 160);
@endphp

<section class="sp-listing-hero">
    <div class="sp-listing-hero__bg" aria-hidden="true">
        <img src="{{ $aboutImage }}" alt="">
    </div>
    <div class="wrap sp-listing-hero__inner">
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>درباره ما</span>
        </nav>
        <h1>درباره سپاسیران</h1>
        <p>{{ $lede }}</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="sp-trust">
            <div class="sp-trust__media">
                <img src="{{ $aboutImage }}" alt="سپاسیران" loading="lazy">
            </div>
            <div class="sp-trust__body">
                <p class="sp-trust__eyebrow">
                    <span class="sp-section-head__icon sp-section-head__icon--inline" aria-hidden="true"><x-sp-icon name="trust" /></span>
                    داستان ما
                </p>
                <h2>{{ $latestBlog->title ?? 'بازار آرام برای حیوان خانگی' }}</h2>
                <p>{!! nl2br(e(strip_tags($latestBlog->content ?? $aboutText))) !!}</p>
                <div class="sp-trust__actions">
                    <a class="btn btn-primary" href="{{ route('frontend.dog.supply.index') }}">مشاهده سگ‌ها</a>
                    <a class="btn btn-ghost" href="{{ route('frontend.contact.index') }}">تماس با ما</a>
                </div>
            </div>
        </div>
    </div>
</section>

@if(!empty($statistics))
<section class="section" style="padding-top:0">
    <div class="wrap">
        <x-sp-section-head
            title="سپاسیران در یک نگاه"
            subtitle="اعداد واقعی از مربی، کلینیک، پرورش‌دهنده و آگهی‌ها."
            icon="spark"
        />
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

<section class="section" style="padding-top:0">
    <div class="wrap">
        <x-sp-section-head
            title="چه کارهایی می‌کنیم؟"
            subtitle="یک مسیر شفاف برای صاحب حیوان خانگی."
            icon="services"
        />
        <div class="sp-service-lanes sp-service-lanes--compact">
            <a class="sp-service-lane" href="{{ route('frontend.coaches.index') }}">
                <span class="sp-service-lane__icon"><x-sp-icon name="coach" /></span>
                <h3>مربی</h3>
            </a>
            <a class="sp-service-lane" href="{{ route('frontend.clinics.index') }}">
                <span class="sp-service-lane__icon"><x-sp-icon name="clinic" /></span>
                <h3>کلینیک</h3>
            </a>
            <a class="sp-service-lane" href="{{ route('frontend.clinic.insurance.index') }}">
                <span class="sp-service-lane__icon"><x-sp-icon name="insurance" /></span>
                <h3>بیمه</h3>
            </a>
            <a class="sp-service-lane" href="{{ route('frontend.online.courses') }}">
                <span class="sp-service-lane__icon"><x-sp-icon name="course" /></span>
                <h3>دوره</h3>
            </a>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <x-sp-section-head
            title="سوالات متداول"
            subtitle="پاسخ‌های کوتاه برای شروع مطمئن."
            icon="steps"
        />
        <div class="sp-provider__list">
            @foreach([
                ['q' => 'چطور رزرو کنم؟', 'a' => 'از منوی خدمات وارد رزرو شوید، سگ و شهر را انتخاب کنید و درخواست را ثبت کنید.'],
                ['q' => 'پشتیبانی چگونه است؟', 'a' => 'از آیکون چت شناور تیکت باز کنید؛ تیم ادمین همان‌جا پاسخ می‌دهد.'],
                ['q' => 'آیا دوره و بیمه هم دارید؟', 'a' => 'بله — دوره‌های آنلاین و طرح‌های بیمه مدت‌دار در سپاسیران فعال است.'],
            ] as $faq)
                <div class="sp-provider__list-item">
                    <i class="fas fa-question-circle"></i>
                    <div>
                        <strong>{{ $faq['q'] }}</strong>
                        <p>{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <div class="sp-final-cta">
            <div>
                <div class="sp-section-head__title-row" style="margin-bottom:8px">
                    <span class="sp-section-head__icon" aria-hidden="true"><x-sp-icon name="cta" /></span>
                    <h2 style="margin:0">آماده‌اید شروع کنید؟</h2>
                </div>
                <p>از سگ‌ها ببینید یا با ما در تماس باشید.</p>
            </div>
            <div class="sp-final-cta__actions">
                <a class="btn btn-primary" href="{{ route('frontend.dog.supply.index') }}">مشاهده سگ‌ها</a>
                <a class="btn btn-ghost" href="{{ route('frontend.contact.index') }}">تماس</a>
            </div>
        </div>
    </div>
</section>
@endsection