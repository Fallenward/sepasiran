@extends('layouts.app')

@section('title', 'سگ‌ها')

@section('content')
<div class="wrap sp-listing" style="padding-top:24px;padding-bottom:48px">
    <header class="sp-listing__head">
        <div>
            <h1 class="sp-page-title">سگ‌ها</h1>
            <p class="sp-count num">
                {{ method_exists($dogs, 'total') ? $dogs->total() : $dogs->count() }} آگهی
            </p>
        </div>
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>سگ‌ها</span>
        </nav>
    </header>

    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form :filters="$filters" />
        </div>

        <div class="sp-listing__results">
            <div class="grid grid-3">
                @forelse($dogs as $dog)
                    @include('components.dog-card', ['item' => $dog])
                @empty
                    <div class="panel" style="grid-column:1/-1;text-align:center">
                        <p style="margin:0;color:var(--sp-text-muted)">سگی یافت نشد. فیلترها را تغییر دهید.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                <x-load-more-button :hasMore="$dogs->hasMorePages()" :nextPage="$dogs->currentPage() + 1" />
            </div>
        </div>
    </div>
</div>

<x-scripts.filter-submit />
<x-scripts.load-more-dogs />
@endsection