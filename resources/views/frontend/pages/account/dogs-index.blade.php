@extends('frontend.layouts.app')

@section('title', 'سگ‌های من — سپاسیران')

@section('content')
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-nav')

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="fas fa-paw"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
                    <h1 class="sp-dash__title">سگ‌های من</h1>
                    <p class="sp-dash__lede">سگ‌های ثبت‌شده در حساب برای رزرو خدمت و مدیریت مشخصات.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>افزودن سگ</strong>
                    <p>نام، نژاد و مشخصات را وارد کنید.</p>
                </div>
                <a class="sp-btn sp-btn--primary" href="{{ route('account.dogs.create') }}">سگ جدید</a>
            </aside>
        </section>

        @if(session('success'))
            <div class="sp-dash__flash">{{ session('success') }}</div>
        @endif

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>لیست</h2>
                <a href="{{ $backUrl }}">داشبورد</a>
            </header>
            <div class="sp-dash__list">
                @forelse($dogs as $dog)
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="fas fa-dog"></i></span>
                        <div>
                            <strong>{{ $dog->title }}</strong>
                            <p class="sp-dash__muted">{{ optional($dog->breed)->name ?? 'نژاد نامشخص' }} · {{ $dog->status }}</p>
                        </div>
                        <span class="sp-dash__chip is-active">{{ $dog->age ? $dog->age.' ماه' : '—' }}</span>
                    </article>
                @empty
                    <div class="sp-dash__empty">
                        <div class="sp-dash__empty-visual" aria-hidden="true"></div>
                        <p>هنوز سگی ثبت نکرده‌اید.</p>
                        <a class="sp-btn sp-btn--primary" href="{{ route('account.dogs.create') }}">افزودن اولین سگ</a>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
