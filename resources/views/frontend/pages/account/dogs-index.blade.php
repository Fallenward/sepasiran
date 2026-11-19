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
                    <p>نام، نژاد، رنگ و تصاویر را وارد کنید.</p>
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
                        @if($dog->primaryImageUrl())
                            <img class="sp-dash__row-thumb" src="{{ $dog->primaryImageUrl() }}" alt="">
                        @else
                            <span class="sp-dash__row-icon"><i class="fas fa-dog"></i></span>
                        @endif
                        <div>
                            <strong>{{ $dog->title }}</strong>
                            <p class="sp-dash__muted">
                                {{ optional($dog->breed)->name ?? 'نژاد نامشخص' }}
                                @if($dog->color) · {{ $dog->color }} @endif
                                · {{ $dog->status }}
                            </p>
                        </div>
                        <div class="sp-dash__row-meta">
                            <span class="sp-dash__chip is-active"><span class="num">{{ $dog->age ? $dog->age : '—' }}</span> ماه</span>
                            <a class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px" href="{{ route('account.dogs.edit', $dog) }}">ویرایش</a>
                            <form method="POST" action="{{ route('account.dogs.destroy', $dog) }}" onsubmit="return confirm('این سگ حذف شود؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px;color:var(--sp-danger)">حذف</button>
                            </form>
                        </div>
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