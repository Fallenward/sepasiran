@extends('frontend.layouts.app')

@section('title', 'بیمه حیوانات — سپاسیران')

@section('content')
<section class="sp-ins-index__hero">
    <div class="sp-ins-index__hero-bg" style="background-image:url('{{ asset('images/demo/hero/hero-03.jpg') }}')" aria-hidden="true"></div>
    <div class="sp-ins-index__hero-scrim" aria-hidden="true"></div>
    <div class="wrap sp-ins-index__hero-inner">
        <p class="sp-ins__brand">سپاسیران</p>
        <h1>بیمه حیوانات</h1>
        <p class="sp-ins__lede">طرح و مدت را انتخاب کنید؛ پس از خرید، مجوز پوشش در داشبوردتان فعال می‌شود.</p>
        @auth
            <a class="btn btn-primary" href="{{ route('account.insurance-licenses.index') }}">مجوزهای من</a>
        @endauth
    </div>
</section>

<div class="wrap sp-listing sp-ins-index" style="padding-top:28px;padding-bottom:56px">
    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form :filters="$filters" />
        </div>
        <div class="sp-listing__results">
            <div class="grid grid-3">
                @forelse($insurances as $insurance)
                    <div class="sp-listing-card">
                        <x-insurance-card :item="$insurance" />
                    </div>
                @empty
                    <div class="panel" style="grid-column:1/-1;text-align:center">
                        <p style="margin:0;color:var(--sp-text-muted)">بیمه‌ای یافت نشد.</p>
                    </div>
                @endforelse
            </div>
            @if(method_exists($insurances, 'hasPages') && $insurances->hasPages())
                <nav class="sp-listing__pager" aria-label="صفحه‌بندی">
                    <div class="sp-pagination">{{ $insurances->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
                </nav>
            @endif
        </div>
    </div>
</div>

<x-scripts.filter-submit />
@endsection