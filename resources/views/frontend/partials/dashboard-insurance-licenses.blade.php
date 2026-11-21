@php
    $tz = config('app.timezone', 'Asia/Tehran');
    $licenses = $insuranceLicenses ?? collect();
@endphp
<section class="sp-dash__panel">
    <header class="sp-dash__panel-head">
        <h2>مجوزهای بیمه</h2>
        <a href="{{ route('account.insurance-licenses.index') }}">همه مجوزها</a>
    </header>
    <div class="sp-dash__list">
        @forelse($licenses as $license)
            @php $active = $license->isCurrentlyActive(); @endphp
            <article class="sp-dash__row">
                <span class="sp-dash__row-icon"><i class="fas fa-shield-alt"></i></span>
                <div>
                    <strong>{{ $license->insurance?->name ?? 'بیمه' }}</strong>
                    <p class="sp-dash__muted">
                        <span class="num">{{ $license->license_code }}</span>
                        · {{ $license->plan?->name ?? 'طرح' }}
                        · تا <span class="num">{{ optional($license->ends_at)->timezone($tz)->format('Y/m/d') }}</span>
                    </p>
                </div>
                <div class="sp-dash__row-meta">
                    <span class="sp-dash__chip {{ $active ? 'is-active' : 'is-danger' }}">{{ $active ? 'فعال' : 'منقضی' }}</span>
                </div>
            </article>
        @empty
            <p class="sp-dash__empty-inline">مجوز فعالی ندارید.
                <a href="{{ route('frontend.clinic.insurance.index') }}">خرید بیمه</a>
            </p>
        @endforelse
    </div>
</section>
