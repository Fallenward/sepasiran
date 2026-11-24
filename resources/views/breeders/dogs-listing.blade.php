@extends('frontend.layouts.app')

@section('title', 'سگ‌های من — سپاسیران')

@section('content')
<div class="sp-dash">
    <div class="sp-dash__inner">
                @include('frontend.partials.dashboard-nav')
<section class="sp-dash__hero">
            <div class="sp-dash__hero-copy">
                <p class="sp-dash__eyebrow">پرورش‌دهنده</p>
                <h1 class="sp-dash__title">سگ‌های من</h1>
                <p class="sp-dash__lede">آگهی‌های ثبت‌شده و وضعیت هر کدام.</p>
            </div>
            <a class="sp-btn sp-btn--primary" href="{{ route('breeder.dashboard-create') }}">افزودن سگ</a>
        </section>

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>لیست</h2>
                <a href="{{ route('breeder.dashboard') }}">داشبورد</a>
            </header>
            <div class="sp-dash__list">
                @forelse(($dogs ?? collect()) as $dog)
                    <article class="sp-dash__row">
                        <div>
                            <strong>{{ $dog->title ?? ('سگ #' . $dog->id) }}</strong>
                            <p class="sp-dash__muted">{{ optional($dog->breed)->name ?? 'نژاد نامشخص' }}</p>
                        </div>
                        <span class="sp-dash__chip is-active">{{ $dog->status }}</span>
                    </article>
                @empty
                    <div class="sp-dash__empty">
                        <p>سگی ثبت نشده است.</p>
                        <a class="sp-btn sp-btn--primary" href="{{ route('breeder.dashboard-create') }}">افزودن اولین سگ</a>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
