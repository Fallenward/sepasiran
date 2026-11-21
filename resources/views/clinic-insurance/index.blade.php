@extends('frontend.layouts.app')

@section('title', 'بیمه حیوانات — سپاسیران')

@section('content')
<div class="sp-ins-index-m">
    <section class="sp-ins-index-m__hero">
        <div class="sp-ins-index-m__photo" style="background-image:url('{{ asset('images/demo/hero/hero-03.jpg') }}')" aria-hidden="true"></div>
        <div class="sp-ins-index-m__copy">
            <p class="sp-ins-m__brand">سپاسیران</p>
            <h1>بیمه حیوانات</h1>
            <p>طرح را بخرید؛ مجوز مدت‌دار در داشبورد می‌آید.</p>
            <div class="sp-ins-index-m__actions">
                @auth
                    <a class="sp-ins-m__cta" href="{{ route('account.insurance-licenses.index') }}">مجوزهای من</a>
                @else
                    <a class="sp-ins-m__cta" href="{{ route('login', ['redirect' => route('frontend.clinic.insurance.index')]) }}">ورود</a>
                @endauth
            </div>
        </div>
    </section>

    <div class="wrap sp-ins-index-m__list">
        <form class="sp-ins-index-m__search" method="get" action="{{ route('frontend.clinic.insurance.index') }}">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="جستجوی بیمه…" aria-label="جستجو">
            <button type="submit">برو</button>
        </form>

        <div class="sp-ins-index-m__grid">
            @forelse($insurances as $insurance)
                <a class="sp-ins-index-m__item" href="{{ route('frontend.clinic.insurance.show', $insurance->id) }}">
                    <span class="sp-ins-index-m__thumb" style="background-image:url('{{ $insurance->logoUrl() }}')"></span>
                    <span class="sp-ins-index-m__meta">
                        <strong>{{ $insurance->name }}</strong>
                        <small>{{ \Illuminate\Support\Str::limit(strip_tags($insurance->description ?? ''), 64) }}</small>
                        @php
                            $from = $insurance->activePlans->min('price') ?? null;
                        @endphp
                        @if($from)
                            <em>از <span class="num">{{ number_format((int) $from) }}</span> ت</em>
                        @endif
                    </span>
                </a>
            @empty
                <p class="sp-ins-m__empty">بیمه‌ای یافت نشد.</p>
            @endforelse
        </div>

        @if(method_exists($insurances, 'hasPages') && $insurances->hasPages())
            <nav class="sp-ins-index-m__pager">{{ $insurances->onEachSide(1)->links('pagination::bootstrap-5') }}</nav>
        @endif
    </div>
</div>
@endsection
