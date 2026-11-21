@extends('layouts.app')

@section('title', trim(($trainer->name ?? '').' '.($trainer->family ?? '')).' — کلینیک')

@section('content')
@php
    $fullName = trim(($trainer->name ?? '').' '.($trainer->family ?? ''));
    $about = trim((string) ($trainer->bio ?? $trainer->description ?? ''));
    if ($about === '') {
        $about = $fullName.' کلینیک دامپزشکی است. از این صفحه می‌توانید مستقیم نوبت ویزیت یا خدمات کلینیکی رزرو کنید.';
    }
    $specialties = $specialties ?? collect();
    $clinicServices = $clinicServices ?? [
        ['title' => 'معاینه عمومی', 'desc' => 'بررسی وضعیت سلامت و مشاوره اولیه.'],
        ['title' => 'واکسیناسیون', 'desc' => 'برنامه واکسن و پیشگیری.'],
        ['title' => 'درمان و پیگیری', 'desc' => 'مراقبت‌های درمانی و پیگیری وضعیت.'],
    ];
@endphp

<x-provider-profile
    :provider="$trainer"
    role-label="کلینیک"
    :lede="$cityName ? 'کلینیک در '.$cityName.' — رزرو مستقیم نوبت' : 'رزرو مستقیم نوبت و خدمات دامپزشکی'"
    :about="$about"
    :book-url="$bookUrl"
    book-label="رزرو نوبت کلینیک"
    book-hint="به‌جای تماس تلفنی، نوبت را آنلاین ثبت کنید و این کلینیک را به‌عنوان ارائه‌دهنده انتخاب کنید."
    :city-name="$cityName"
    :specialties="$specialties"
    :facts="$facts"
    hero-image="images/demo/hero/hero-03.jpg"
>
    <section class="sp-provider__panel">
        <h2>خدمات قابل رزرو</h2>
        <div class="sp-provider__grid">
            @foreach($clinicServices as $svc)
                <a class="sp-provider__card" href="{{ $bookUrl }}">
                    <h3>{{ $svc['title'] }}</h3>
                    <p>{{ $svc['desc'] }}</p>
                    <span class="sp-provider__chip is-accent">رزرو نوبت</span>
                </a>
            @endforeach
        </div>
    </section>

    @if($specialties->isNotEmpty())
        <section class="sp-provider__panel">
            <h2>تخصص‌ها</h2>
            <div class="sp-provider__tags">
                @foreach($specialties as $spec)
                    <span class="sp-provider__tag">{{ $spec->name ?? $spec }}</span>
                @endforeach
            </div>
        </section>
    @endif

    <section class="sp-provider__panel">
        <h2>اطلاعات مراجعه</h2>
        <div class="sp-provider__list">
            @if(!empty($workingHours))
                <div class="sp-provider__list-item"><i class="fas fa-clock"></i><div><strong>ساعت کاری</strong><p>{{ $workingHours }}</p></div></div>
            @endif
            @if(!empty($animals))
                <div class="sp-provider__list-item"><i class="fas fa-dog"></i><div><strong>حیوانات تحت پوشش</strong><p>{{ $animals }}</p></div></div>
            @endif
            @if(!empty($address))
                <div class="sp-provider__list-item"><i class="fas fa-map-marker-alt"></i><div><strong>آدرس</strong><p>{{ $address }}</p></div></div>
            @endif
            <div class="sp-provider__list-item"><i class="fas fa-calendar-check"></i><div><strong>نوبت‌دهی آنلاین</strong><p>از دکمه رزرو استفاده کنید؛ تماس لازم نیست.</p></div></div>
        </div>
    </section>

    <x-slot:aside>
        <h2>بعد از رزرو</h2>
        <p class="sp-provider__cta-note" style="margin-top:10px">وضعیت درخواست در بخش رزروهای حساب شما نمایش داده می‌شود و کلینیک می‌تواند بپذیرد یا زمان را هماهنگ کند.</p>
    </x-slot:aside>
</x-provider-profile>
@endsection