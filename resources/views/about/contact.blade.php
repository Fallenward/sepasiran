@extends('layouts.app')

@section('title', 'تماس با ما')

@section('content')
@php
    $phone = optional($settings ?? null)->contact_phone
        ?? optional($settings ?? null)->footer_setting_phone
        ?? '۰۹۱۲۰۰۰۰۰۰۰';
    $email = optional($settings ?? null)->contact_email
        ?? optional($settings ?? null)->footer_setting_email
        ?? 'hello@sepasiran.test';
    $address = optional($settings ?? null)->contact_address
        ?? optional($settings ?? null)->footer_setting_address
        ?? 'تهران';
@endphp

<section class="sp-listing-hero">
    <div class="sp-listing-hero__bg" aria-hidden="true">
        <img src="{{ asset('images/demo/hero/hero-02.jpg') }}" alt="">
    </div>
    <div class="wrap sp-listing-hero__inner">
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>تماس با ما</span>
        </nav>
        <h1>تماس با ما</h1>
        <p>پیام بگذارید یا از پشتیبانی زنده استفاده کنید — پاسخ شفاف و سریع.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        @if(session('success'))
            <div class="sp-provider__panel" style="margin-bottom:20px;border-color:rgba(126,200,160,.45)">{{ session('success') }}</div>
        @endif

        <div class="sp-dog-show__layout">
            <div class="sp-dog-show__main">
                <section class="sp-provider__panel">
                    <h2>ارسال پیام</h2>
                    <p class="sp-provider__cta-note">برای پیگیری زنده، چت پشتیبانی را باز کنید؛ این فرم برای پیام‌های عمومی است.</p>
                    <form method="POST" action="{{ route('frontend.contact.store') }}" class="sp-contact-form" style="display:grid;gap:14px;margin-top:16px">
                        @csrf
                        <div class="field" style="margin:0">
                            <label for="name">نام و نام خانوادگی</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                            @error('name')<p class="sp-support__hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field" style="margin:0">
                            <label for="email">ایمیل</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                            @error('email')<p class="sp-support__hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field" style="margin:0">
                            <label for="phone">شماره تماس</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required class="num">
                            @error('phone')<p class="sp-support__hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field" style="margin:0">
                            <label for="message">پیام شما</label>
                            <textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')<p class="sp-support__hint">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="sp-btn sp-btn--primary">ارسال پیام</button>
                    </form>
                </section>
            </div>

            <aside class="sp-dog-show__aside">
                <div class="sp-provider__panel sp-provider__cta">
                    <h2 style="margin:0">راه‌های ارتباطی</h2>
                    <div class="sp-provider__facts" style="margin-top:12px">
                        <div class="sp-provider__fact">
                            <span>تلفن</span>
                            <strong class="num">{{ $phone }}</strong>
                        </div>
                        <div class="sp-provider__fact">
                            <span>ایمیل</span>
                            <strong>{{ $email }}</strong>
                        </div>
                        <div class="sp-provider__fact">
                            <span>آدرس</span>
                            <strong>{{ $address }}</strong>
                        </div>
                    </div>
                    <a class="sp-btn sp-btn--ghost" style="width:100%;margin-top:12px" href="{{ route('frontend.support.index') }}">پشتیبانی زنده</a>
                    <button type="button" class="sp-btn sp-btn--primary" style="width:100%;margin-top:8px" data-sp-support-open>باز کردن چت</button>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection