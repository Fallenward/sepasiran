@extends('frontend.layouts.app')

@section('title', 'دوره‌های من — سپاسیران')

@section('content')
@php $tz = config('app.timezone', 'Asia/Tehran'); @endphp
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-nav')

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="fas fa-graduation-cap"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
                    <h1 class="sp-dash__title">دوره‌های خریداری‌شده</h1>
                    <p class="sp-dash__lede">هر خرید دوره، دسترسی کامل به سرفصل‌ها و درس‌ها را در حساب شما باز می‌کند.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>دوره جدید</strong>
                    <p>از فهرست دوره‌ها خرید کنید.</p>
                </div>
                <a class="sp-btn sp-btn--primary" href="{{ route('frontend.online.courses') }}">مشاهده دوره‌ها</a>
            </aside>
        </section>

        @if(session('success'))
            <section class="sp-dash__panel" style="border-color:rgba(126,200,160,.45)">
                <p style="margin:0">{{ session('success') }}</p>
            </section>
        @endif

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>دسترسی‌های من</h2>
                <a href="{{ route('user.dashboard') }}">داشبورد</a>
            </header>
            <div class="sp-dash__list">
                @forelse($enrollments as $enrollment)
                    @php $course = $enrollment->course; @endphp
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="fas fa-play-circle"></i></span>
                        <div>
                            <strong>{{ $course?->title ?? 'دوره' }}</strong>
                            <p class="sp-dash__muted">
                                کد: <span class="num">{{ $enrollment->enrollment_code }}</span>
                                @if($course?->category?->name)
                                    · {{ $course->category->name }}
                                @endif
                            </p>
                            <p class="sp-dash__muted">
                                خرید
                                <span class="num">{{ optional($enrollment->purchased_at)->timezone($tz)->format('Y/m/d H:i') }}</span>
                            </p>
                        </div>
                        <div class="sp-dash__row-meta">
                            <span class="sp-dash__chip is-active">فعال</span>
                            <span class="sp-dash__muted num">{{ number_format((int) $enrollment->amount_paid) }} ت</span>
                            @if($course?->slug)
                                <a class="sp-btn sp-btn--ghost" href="{{ route('frontend.courses.show', $course->slug) }}">مشاهده</a>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="sp-dash__empty-inline">هنوز دوره‌ای نخریده‌اید. از صفحه دوره‌ها شروع کنید.</p>
                @endforelse
            </div>
            @if(method_exists($enrollments, 'hasPages') && $enrollments->hasPages())
                <div class="sp-pagination" style="margin-top:16px">{{ $enrollments->links('pagination::bootstrap-5') }}</div>
            @endif
        </section>
    </div>
</div>
@endsection
