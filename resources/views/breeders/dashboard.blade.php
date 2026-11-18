@extends('frontend.layouts.app')

@section('title', ($title ?? 'داشبورد پرورش‌دهنده') . ' — سپاسیران')

@section('content')
<div class="sp-dash">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-hero', [
            'user' => $user,
            'roleLabel' => $roleLabel ?? 'پرورش‌دهنده',
            'lede' => 'سگ‌های شما، درخواست‌های خرید و ثبت آگهی جدید.',
            'primaryAction' => ['label' => 'افزودن سگ', 'url' => route('breeder.dashboard-create')],
        ])

        @include('frontend.partials.dashboard-metrics', [
            'metrics' => [
                ['label' => 'کل سگ‌ها', 'value' => number_format($totalDogs), 'hint' => 'متعلق به شما'],
                ['label' => 'آماده عرضه', 'value' => number_format($readyDogs), 'hint' => 'قابل نمایش'],
                ['label' => 'درخواست باز', 'value' => number_format($pendingBookings), 'hint' => 'نیاز به پیگیری'],
            ],
        ])

        @include('frontend.partials.dashboard-actions', [
            'actions' => [
                ['label' => 'افزودن سگ', 'hint' => 'ثبت آگهی جدید', 'url' => route('breeder.dashboard-create')],
                ['label' => 'لیست سگ‌های من', 'hint' => 'مدیریت آگهی‌ها', 'url' => route('breeder.dogs.listing')],
                ['label' => 'پروفایل', 'hint' => 'اطلاعات پرورش‌دهنده', 'url' => route('breeder.profile')],
                ['label' => 'کیف پول', 'hint' => number_format($walletBalance ?? 0) . ' تومان', 'url' => route('wallet.index')],
            ],
        ])

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>وضعیت گله</h2>
            </header>
            <div class="sp-dash__stat-row">
                <span>در انتظار تأیید: <strong class="latin">{{ number_format($pendingDogs) }}</strong></span>
                <span>فروخته‌شده: <strong class="latin">{{ number_format($soldDogs) }}</strong></span>
                <span>در صف: <strong class="latin">{{ number_format($waitingDogs) }}</strong></span>
                <span>رزرو تأییدشده: <strong class="latin">{{ number_format($openBookings) }}</strong></span>
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
                        <div>
                            <strong>{{ $dog->title ?? ('سگ #' . $dog->id) }}</strong>
                            <p class="sp-dash__muted">{{ $dog->status }}</p>
                        </div>
                        <span class="sp-dash__chip is-active">{{ $dog->status }}</span>
                    </article>
                @empty
                    <div class="sp-dash__empty">
                        <p>هنوز سگی ثبت نکرده‌اید.</p>
                        <a class="sp-btn sp-btn--primary" href="{{ route('breeder.dashboard-create') }}">اولین سگ را اضافه کنید</a>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
