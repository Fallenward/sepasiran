@extends('frontend.layouts.app')

@section('title', 'کیف پول — سپاسیران')

@section('content')
@php
    $statusLabel = fn ($status) => $status === 'completed' ? ['موفق', 'is-active'] : ['ناموفق', 'is-danger'];
@endphp
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
                @include('frontend.partials.dashboard-nav')
<div class="sp-dash__brand" aria-label="برند">
            <p class="sp-dash__brand-fa">سپاسیران</p>
            <p class="sp-dash__brand-en latin">Wallet</p>
        </div>

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="fas fa-wallet"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel ?? 'حساب' }}</p>
                    <h1 class="sp-dash__title">کیف پول</h1>
                    <p class="sp-dash__lede">موجودی، واریز و برداشت‌های حساب شما در یک نگاه.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>بازگشت به داشبورد</strong>
                    <p>به خلاصه حساب نقش خود برگردید.</p>
                </div>
                <a class="sp-btn sp-btn--ghost" href="{{ $backUrl ?? route('user.dashboard') }}">داشبورد</a>
            </aside>
        </section>

        @if(session('success'))
            <div class="sp-dash__flash">{{ session('success') }}</div>
        @endif

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>افزایش موجودی</h2>
            </header>
            <form class="sp-dash__inline-form" method="POST" action="{{ route('wallet.deposit') }}">
                @csrf
                <div class="field" style="margin:0;flex:1;min-width:180px">
                    <label for="amount">مبلغ (تومان)</label>
                    <input id="amount" class="num" name="amount" type="number" min="10000" step="1000" value="{{ old('amount', 100000) }}" required>
                    @error('amount')<p class="auth-alert">{{ $message }}</p>@enderror
                </div>
                <button class="sp-btn sp-btn--primary" type="submit">افزودن به کیف پول</button>
            </form>
            <p class="sp-dash__muted" style="margin-top:10px">مبلغ انتخابی به موجودی حساب اضافه و در تراکنش‌ها ثبت می‌شود.</p>
        </section>

        @include('frontend.partials.dashboard-metrics', [
            'metrics' => [
                ['label' => 'موجودی کیف پول', 'value' => number_format($walletBalance) . ' ت', 'hint' => 'قابل استفاده', 'icon' => 'fas fa-wallet'],
                ['label' => 'درآمد این ماه', 'value' => number_format($monthlyIncome) . ' ت', 'hint' => 'واریزهای موفق', 'icon' => 'fas fa-arrow-down'],
                ['label' => 'هزینه این ماه', 'value' => number_format($monthlyExpense) . ' ت', 'hint' => 'برداشت‌ها', 'icon' => 'fas fa-arrow-up'],
            ],
        ])

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>سود خالص این ماه</h2>
                <span class="sp-dash__chip {{ $netProfit >= 0 ? 'is-active' : 'is-danger' }}"><span class="num">{{ number_format($netProfit) }}</span> ت</span>
            </header>
            <p class="sp-dash__muted">اختلاف واریز و برداشت تکمیل‌شده در ماه جاری.</p>
        </section>

        @include('frontend.partials.dashboard-finance-chart', [
            'financeChart' => $financeChart,
            'chartTitle' => 'نمودار تراکنش‌های مالی',
            'chartId' => 'spWalletFinanceChart',
            'chartLinkUrl' => route('wallet.index'),
            'chartLinkLabel' => 'به‌روز',
        ])

        <section class="sp-dash__panel">
            <header class="sp-dash__panel-head">
                <h2>تراکنش‌های اخیر</h2>
            </header>

            <div class="sp-dash__list">
                @forelse($transactions as $transaction)
                    @php [$stLabel, $stClass] = $statusLabel($transaction->status); @endphp
                    <article class="sp-dash__row">
                        <span class="sp-dash__row-icon">
                            <i class="fas {{ $transaction->amount >= 0 ? 'fa-plus' : 'fa-minus' }}"></i>
                        </span>
                        <div>
                            <strong>{{ $transaction->description ?: ($transaction->amount >= 0 ? 'واریز' : 'برداشت') }}</strong>
                            <p class="sp-dash__muted">
                                {{ class_exists(\Morilog\Jalali\Jalalian::class) ? \Morilog\Jalali\Jalalian::fromDateTime($transaction->created_at)->format('Y/m/d') : $transaction->created_at->format('Y-m-d') }}
                                · {{ $transaction->amount >= 0 ? 'واریزی' : 'برداشت' }}
                            </p>
                        </div>
                        <div class="sp-dash__row-meta">
                            <span class="sp-dash__chip {{ $stClass }}">{{ $stLabel }}</span>
                            <span class="sp-dash__muted"><span class="num">{{ number_format((float) $transaction->amount) }}</span> ت</span>
                        </div>
                    </article>
                @empty
                    <div class="sp-dash__empty">
                        <div class="sp-dash__empty-visual" aria-hidden="true"></div>
                        <p>هنوز تراکنشی ثبت نشده است.</p>
                    </div>
                @endforelse
            </div>

            @if(method_exists($transactions, 'links') && $transactions->hasPages())
                <div class="sp-dash__pager">
                    {{ $transactions->links() }}
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
