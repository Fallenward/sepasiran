@extends('frontend.layouts.app')

@section('title', ($title ?? 'داشبورد پرورش‌دهنده') . ' — سپاسیران')

@section('content')
<div class="sp-dash sp-dash--breeder">
    <div class="sp-dash__inner">
                @include('frontend.partials.dashboard-nav')
@include('frontend.partials.dashboard-hero', [
            'user' => $user,
            'roleLabel' => $roleLabel ?? 'پرورش‌دهنده',
            'lede' => 'سگ‌های شما، درخواست‌های باز و ثبت آگهی جدید در یک قاب.',
            'ctaTitle' => 'آگهی جدید',
            'ctaHint' => 'سگ تازه را با مشخصات و وضعیت عرضه ثبت کنید.',
            'primaryAction' => ['label' => 'افزودن سگ', 'url' => route('breeder.dashboard-create')],
        ])

        @include('frontend.partials.dashboard-metrics', [
            'metrics' => [
                ['label' => 'کل سگ‌ها', 'value' => number_format($totalDogs), 'hint' => 'متعلق به شما', 'icon' => 'fas fa-paw'],
                ['label' => 'آماده عرضه', 'value' => number_format($readyDogs), 'hint' => 'قابل نمایش', 'icon' => 'fas fa-check-circle'],
                ['label' => 'درخواست باز', 'value' => number_format($pendingBookings), 'hint' => 'نیاز به پیگیری', 'icon' => 'fas fa-bell'],
            ],
        ])

        
        @include('frontend.partials.dashboard-finance-chart', [
            'financeChart' => $financeChart,
            'chartTitle' => 'نمودار تراکنش‌های مالی',
            'chartId' => 'spBreederFinanceChart',
        ])


        @include('frontend.partials.dashboard-notifications', [
            'dashNotifications' => $dashNotifications ?? collect(),
        ])

@include('frontend.partials.dashboard-actions', [
            'actions' => [
                ['label' => 'درخواست‌های رزرو', 'hint' => 'درخواست خرید/رزرو مشتریان', 'url' => route('account.bookings.index', ['tab' => 'inbox']), 'icon' => 'fas fa-inbox'],
                ['label' => 'افزودن آگهی سگ', 'hint' => 'ثبت سگ برای فروش', 'url' => route('breeder.dashboard-create'), 'icon' => 'fas fa-plus'],
                ['label' => 'لیست آگهی‌ها', 'hint' => 'مدیریت سگ‌های عرضه‌شده', 'url' => route('breeder.dogs.listing'), 'icon' => 'fas fa-list-ul'],
                ['label' => 'سگ‌های حساب', 'hint' => 'سگ‌های شخصی حساب', 'url' => route('account.dogs.index'), 'icon' => 'fas fa-paw'],
                ['label' => 'کیف پول', 'hint' => number_format($walletBalance ?? 0) . ' تومان', 'url' => route('wallet.index'), 'icon' => 'fas fa-wallet'],
            ],
        ])

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>وضعیت گله</h2>
            </header>
            <div class="sp-dash__stat-row">
                <div class="sp-dash__stat-pill"><span>در انتظار تأیید</span><strong class="num">{{ number_format($pendingDogs) }}</strong></div>
                <div class="sp-dash__stat-pill"><span>فروخته‌شده</span><strong class="num">{{ number_format($soldDogs) }}</strong></div>
                <div class="sp-dash__stat-pill"><span>در صف</span><strong class="num">{{ number_format($waitingDogs) }}</strong></div>
                <div class="sp-dash__stat-pill"><span>رزرو تأییدشده</span><strong class="num">{{ number_format($openBookings) }}</strong></div>
            </div>
        </section>

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>آخرین سگ‌ها</h2>
                <a href="{{ route('breeder.dogs.listing') }}">مشاهده همه</a>
            </header>
            <div class="sp-dash__list">
                @forelse($recentDogs as $dog)
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon"><i class="fas fa-dog"></i></span>
                        <div>
                            <strong>{{ $dog->title ?? ('سگ #' . $dog->id) }}</strong>
                            <p class="sp-dash__muted">{{ $dog->status }}</p>
                        </div>
                        <span class="sp-dash__chip is-active">{{ $dog->status }}</span>
                    </article>
                @empty
                    <div class="sp-dash__empty">
                        <div class="sp-dash__empty-visual" aria-hidden="true"></div>
                        <p>هنوز سگی ثبت نکرده‌اید.</p>
                        <a class="sp-btn sp-btn--primary" href="{{ route('breeder.dashboard-create') }}">اولین سگ را اضافه کنید</a>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
