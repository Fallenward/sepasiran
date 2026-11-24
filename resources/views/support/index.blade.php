@extends('layouts.app')

@section('title', $title ?? 'پشتیبانی')

@section('content')
<section class="sp-listing-hero">
    <div class="sp-listing-hero__bg" aria-hidden="true">
        <img src="{{ asset('images/demo/hero/hero-03.jpg') }}" alt="">
    </div>
    <div class="wrap sp-listing-hero__inner">
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>پشتیبانی</span>
        </nav>
        <h1>پشتیبانی زنده</h1>
        <p>تیکت بسازید، فایل ضمیمه کنید، و با تیم سپاسیران در همان چت شناور گفتگو کنید.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <x-sp-section-head
            title="چطور کار می‌کند؟"
            subtitle="یک آیکون چت در همه صفحات — بدون صفحه جداگانه."
            icon="steps"
        />
        <ol class="sp-steps">
            <li class="sp-step">
                <span class="sp-step__icon"><x-sp-icon name="start" /></span>
                <div>
                    <h3>تیکت باز کنید</h3>
                    <p>موضوع و شرح مشکل را بنویسید؛ پیوست اختیاری است.</p>
                </div>
            </li>
            <li class="sp-step">
                <span class="sp-step__icon"><x-sp-icon name="cta" /></span>
                <div>
                    <h3>هشدار برای ادمین</h3>
                    <p>تیکت جدید فوراً در پنل ادمین اعلام می‌شود.</p>
                </div>
            </li>
            <li class="sp-step">
                <span class="sp-step__icon"><x-sp-icon name="services" /></span>
                <div>
                    <h3>چت زنده</h3>
                    <p>بعد از پاسخ ادمین، گفتگو در ویجت شناور ادامه پیدا می‌کند.</p>
                </div>
            </li>
        </ol>

        <div class="sp-final-cta" style="margin-top:28px">
            <div>
                <h2 style="margin:0 0 8px">آماده ارسال تیکت هستید؟</h2>
                <p style="margin:0">از آیکون چت پایین صفحه استفاده کنید — در همه صفحات در دسترس است.</p>
            </div>
            <div class="sp-final-cta__actions">
                @if($isAuth ?? false)
                    <button type="button" class="btn btn-primary" data-sp-support-open>باز کردن چت پشتیبانی</button>
                @else
                    <a class="btn btn-primary" href="{{ $loginUrl }}">ورود برای ارسال تیکت</a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection