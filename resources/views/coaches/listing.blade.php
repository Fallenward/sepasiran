@extends('frontend.layouts.app')

@section('title', 'فضای مربی‌گری — سپاسیران')

@section('content')
<div class="sp-dash sp-dash--coach">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-nav')

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="fas fa-chalkboard-teacher"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">مربی</p>
                    <h1 class="sp-dash__title">فضای مربی‌گری {{ $coach->name }}</h1>
                    <p class="sp-dash__lede">دوره‌ها، محصولات و میانبر به درخواست‌های رزرو شما.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>درخواست‌های باز</strong>
                    <p><span class="num">{{ number_format($pendingRequests ?? 0) }}</span> مورد در صف پاسخ.</p>
                </div>
                <a class="sp-btn sp-btn--primary" href="{{ route('coaches.courses.create') }}">افزودن دوره</a>
            </aside>
        </section>

        @include('frontend.partials.dashboard-metrics', [
            'metrics' => [
                ['label' => 'دوره‌ها', 'value' => number_format($courses->count()), 'hint' => 'منتشرشده در حساب', 'icon' => 'fas fa-graduation-cap'],
                ['label' => 'محصولات', 'value' => number_format($products->total()), 'hint' => 'مرتبط با مربی', 'icon' => 'fas fa-box'],
                ['label' => 'درخواست باز', 'value' => number_format($pendingRequests ?? 0), 'hint' => 'نیاز به پیگیری', 'icon' => 'fas fa-inbox'],
            ],
        ])

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>دوره‌های من</h2>
                <a href="{{ route('coaches.courses.index') }}">مدیریت دوره‌ها</a>
            </header>
            <div class="sp-dash__list">
                @forelse($courses as $course)
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="fas fa-book"></i></span>
                        <div>
                            <strong>{{ $course->title }}</strong>
                            <p class="sp-dash__muted">{{ $course->status }} · <span class="num">{{ number_format((float) ($course->price ?? 0)) }}</span> ت</p>
                        </div>
                        <span class="sp-dash__chip {{ ($course->is_active ?? false) ? 'is-active' : 'is-done' }}">
                            {{ ($course->is_active ?? false) ? 'فعال' : 'غیرفعال' }}
                        </span>
                    </article>
                @empty
                    <p class="sp-dash__empty-inline">هنوز دوره‌ای ثبت نشده است.</p>
                @endforelse
            </div>
        </section>

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>محصولات مرتبط</h2>
            </header>
            <div class="sp-dash__list">
                @forelse($products as $product)
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="fas fa-tag"></i></span>
                        <div>
                            <strong>{{ $product->title }}</strong>
                            <p class="sp-dash__muted"><span class="num">{{ number_format((float) $product->price) }}</span> ت</p>
                        </div>
                        <span class="sp-dash__chip {{ $product->is_active ? 'is-active' : 'is-done' }}">
                            {{ $product->is_active ? 'فعال' : 'غیرفعال' }}
                        </span>
                    </article>
                @empty
                    <p class="sp-dash__empty-inline">محصولی ثبت نشده است.</p>
                @endforelse
            </div>
            @if(method_exists($products, 'links') && $products->hasPages())
                <div class="sp-dash__pager">{{ $products->links() }}</div>
            @endif
        </section>
    </div>
</div>
@endsection
