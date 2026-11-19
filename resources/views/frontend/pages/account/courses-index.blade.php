@extends('frontend.layouts.app')

@section('title', 'دوره‌های من — سپاسیران')

@section('content')
<div class="sp-dash sp-dash--coach">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-nav')

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="fas fa-graduation-cap"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
                    <h1 class="sp-dash__title">دوره‌های من</h1>
                    <p class="sp-dash__lede">ایجاد، ویرایش و مدیریت دوره‌ها با بخش‌ها و ویدیوهای درسی.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>دوره جدید</strong>
                    <p>عنوان، بخش‌ها و ویدیوها را ثبت کنید.</p>
                </div>
                <a class="sp-btn sp-btn--primary" href="{{ route('coaches.courses.create') }}">افزودن دوره</a>
            </aside>
        </section>

        @if(session('success'))
            <div class="sp-dash__flash">{{ session('success') }}</div>
        @endif

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>لیست دوره‌ها</h2>
                <a href="{{ route('coaches.listing') }}">فضای مربی</a>
            </header>
            <div class="sp-dash__list">
                @forelse($courses as $course)
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="fas fa-book"></i></span>
                        <div>
                            <strong>{{ $course->title }}</strong>
                            <p class="sp-dash__muted">
                                <span class="num">{{ number_format((float) ($course->price ?? 0)) }}</span> ت
                                · {{ $course->status }}
                                · <span class="num">{{ $course->parts_count ?? 0 }}</span> بخش
                                · <span class="num">{{ $course->lessons_count ?? 0 }}</span> درس
                            </p>
                        </div>
                        <div class="sp-dash__row-meta">
                            <a class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px" href="{{ route('coaches.courses.edit', $course) }}">ویرایش</a>
                            <form method="POST" action="{{ route('coaches.courses.destroy', $course) }}" onsubmit="return confirm('حذف شود؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px;color:var(--sp-danger)">حذف</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="sp-dash__empty">
                        <p>هنوز دوره‌ای ندارید.</p>
                        <a class="sp-btn sp-btn--primary" href="{{ route('coaches.courses.create') }}">اولین دوره را بسازید</a>
                    </div>
                @endforelse
            </div>
            @if(method_exists($courses, 'links') && $courses->hasPages())
                <div class="sp-dash__pager">{{ $courses->links() }}</div>
            @endif
        </section>
    </div>
</div>
@endsection