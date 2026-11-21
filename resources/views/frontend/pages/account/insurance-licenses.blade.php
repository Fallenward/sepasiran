@extends('frontend.layouts.app')

@section('title', 'مجوزهای بیمه — سپاسیران')

@section('content')
@php $tz = config('app.timezone', 'Asia/Tehran'); @endphp
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-nav')

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
                    <h1 class="sp-dash__title">مجوزهای بیمه</h1>
                    <p class="sp-dash__lede">هر خرید بیمه یک مجوز با مدت اعتبار مشخص در حساب شما ایجاد می‌کند.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>خرید بیمه جدید</strong>
                    <p>از فهرست بیمه‌ها طرح و مدت را انتخاب کنید.</p>
                </div>
                <a class="sp-btn sp-btn--primary" href="{{ route('frontend.clinic.insurance.index') }}">مشاهده بیمه‌ها</a>
            </aside>
        </section>

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>مجوزهای من</h2>
                <a href="{{ route('user.dashboard') }}">داشبورد</a>
            </header>
            <div class="sp-dash__list">
                @forelse($licenses as $license)
                    @php
                        $active = $license->isCurrentlyActive();
                        $ends = optional($license->ends_at)->timezone($tz);
                    @endphp
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="fas fa-id-card"></i></span>
                        <div>
                            <strong>{{ $license->insurance?->name ?? 'بیمه' }} — {{ $license->plan?->name ?? 'طرح' }}</strong>
                            <p class="sp-dash__muted">کد مجوز: <span class="num">{{ $license->license_code }}</span>
                                @if($license->dog)
                                    · سگ: {{ $license->dog->title }}
                                @endif
                            </p>
                            <p class="sp-dash__muted">
                                از <span class="num">{{ optional($license->starts_at)->timezone($tz)->format('Y/m/d') }}</span>
                                تا <span class="num">{{ $ends?->format('Y/m/d') ?? '—' }}</span>
                                · <span class="num">{{ (int) ($license->plan?->duration_months ?? 0) }}</span> ماه
                            </p>
                        </div>
                        <div class="sp-dash__row-meta">
                            <span class="sp-dash__chip {{ $active ? 'is-active' : 'is-danger' }}">
                                {{ $active ? 'فعال' : 'منقضی' }}
                            </span>
                            <span class="sp-dash__muted num">{{ number_format((int) $license->amount_paid) }} ت</span>
                        </div>
                    </article>
                @empty
                    <p class="sp-dash__empty-inline">هنوز مجوزی ندارید. از صفحه بیمه‌ها یک طرح بخرید.</p>
                @endforelse
            </div>
            @if(method_exists($licenses, 'hasPages') && $licenses->hasPages())
                <div class="sp-pagination" style="margin-top:16px">{{ $licenses->links('pagination::bootstrap-5') }}</div>
            @endif
        </section>
    </div>
</div>
@endsection
