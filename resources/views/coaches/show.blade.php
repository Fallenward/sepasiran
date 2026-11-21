@extends('layouts.app')

@section('title', trim(($trainer->name ?? '').' '.($trainer->family ?? '')).' — مربی')

@section('content')
@php
    $fullName = trim(($trainer->name ?? '').' '.($trainer->family ?? ''));
    $about = trim((string) ($trainer->bio ?? ''));
    if ($about === '') {
        $about = $fullName.' مربی تربیت سگ است. از این صفحه می‌توانید مستقیم جلسه رزرو کنید و جزئیات تخصص و دوره‌های او را ببینید.';
    }
    $courses = $courses ?? collect();
    $specialties = $specialties ?? collect();
@endphp

<x-provider-profile
    :provider="$trainer"
    role-label="مربی"
    :lede="$cityName ? 'مربی در '.$cityName.' — رزرو مستقیم جلسه آموزش و تربیت' : 'رزرو مستقیم جلسه آموزش و تربیت سگ'"
    :about="$about"
    :book-url="$bookUrl"
    book-label="رزرو جلسه با مربی"
    book-hint="سگ خود را انتخاب کنید، زمان را مشخص کنید و مستقیم برای این مربی درخواست بفرستید."
    :city-name="$cityName"
    :specialties="$specialties"
    :facts="$facts"
    hero-image="images/demo/hero/hero-01.jpg"
>
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
        <h2>دوره‌های این مربی</h2>
        @if($courses->isNotEmpty())
            <div class="sp-provider__grid">
                @foreach($courses as $course)
                    @php
                        $thumb = $course->thumbnail
                            ? (str_starts_with($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/'.$course->thumbnail))
                            : asset('images/demo/hero/hero-03.jpg');
                        $courseUrl = !empty($course->slug)
                            ? route('frontend.courses.show', $course->slug)
                            : route('frontend.online.courses');
                    @endphp
                    <a class="sp-provider__card" href="{{ $courseUrl }}">
                        <img src="{{ $thumb }}" alt="{{ $course->title }}">
                        <h3>{{ $course->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) ($course->short_description ?: $course->description)), 110) }}</p>
                        @if($course->price)
                            <span class="num">{{ number_format((float) $course->price) }} ت</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @else
            <p class="sp-provider__empty">هنوز دوره‌ای منتشر نشده؛ می‌توانید مستقیم جلسه خصوصی رزرو کنید.</p>
        @endif
    </section>

    <section class="sp-provider__panel">
        <h2>چطور کار می‌کند؟</h2>
        <div class="sp-provider__list">
            <div class="sp-provider__list-item"><i class="fas fa-1"></i><div><strong>رزرو را شروع کنید</strong><p>سگ و زمان ترجیحی را مشخص کنید.</p></div></div>
            <div class="sp-provider__list-item"><i class="fas fa-2"></i><div><strong>مربی درخواست را می‌بیند</strong><p>این مربی به‌عنوان ارائه‌دهنده انتخاب شده است.</p></div></div>
            <div class="sp-provider__list-item"><i class="fas fa-3"></i><div><strong>پذیرش و هماهنگی</strong><p>بعد از پذیرش، جزئیات جلسه در حساب شماست.</p></div></div>
        </div>
    </section>

    <x-slot:aside>
        <h2>اطلاعات مفید</h2>
        <div class="sp-provider__list" style="margin-top:12px">
            <div class="sp-provider__list-item"><i class="fas fa-paw"></i><div><strong>مناسب برای</strong><p>آموزش پایه تا پیشرفته و اصلاح رفتار.</p></div></div>
            <div class="sp-provider__list-item"><i class="fas fa-clock"></i><div><strong>زمان‌بندی</strong><p>در مرحله رزرو، تاریخ و ساعت را انتخاب می‌کنید.</p></div></div>
        </div>
    </x-slot:aside>
</x-provider-profile>
@endsection