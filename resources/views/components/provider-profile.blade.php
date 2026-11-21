@props([
    'provider',
    'roleLabel',
    'lede',
    'about',
    'bookUrl',
    'bookLabel' => 'رزرو جلسه',
    'bookHint' => 'مستقیماً از این صفحه جلسه رزرو کنید؛ نیازی به تماس تلفنی نیست.',
    'cityName' => null,
    'specialties' => null,
    'facts' => [],
    'heroImage' => 'images/demo/hero/hero-01.jpg',
])
@php
    $fullName = trim(($provider->name ?? '').' '.($provider->family ?? ''));
    $avatar = method_exists($provider, 'avatarUrl') ? $provider->avatarUrl() : null;
    if (! $avatar) {
        $avatar = asset('images/demo/dogs/dog-02.jpg');
    }
    $specialties = collect($specialties ?? []);
@endphp
<div class="sp-provider">
    <section class="sp-provider__hero" style="--sp-provider-hero-image: url('{{ asset($heroImage) }}')">
        <div class="sp-provider__hero-inner">
            <img class="sp-provider__avatar" src="{{ $avatar }}" alt="{{ $fullName }}">
            <div>
                <p class="sp-provider__kicker">{{ $roleLabel }}</p>
                <h1 class="sp-provider__name">{{ $fullName !== '' ? $fullName : $roleLabel }}</h1>
                <p class="sp-provider__lede">{{ $lede }}</p>
                <div class="sp-provider__chips">
                    @if(!empty($provider->is_verified))
                        <span class="sp-provider__chip is-accent"><i class="fas fa-shield-alt" aria-hidden="true"></i> احراز هویت شده</span>
                    @else
                        <span class="sp-provider__chip"><i class="fas fa-user-check" aria-hidden="true"></i> عضو سپاس‌ایران</span>
                    @endif
                    @if($cityName)
                        <span class="sp-provider__chip"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $cityName }}</span>
                    @endif
                    @foreach($specialties->take(4) as $spec)
                        <span class="sp-provider__chip">{{ is_string($spec) ? $spec : ($spec->name ?? '') }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="sp-provider__layout">
        <div style="display:grid;gap:18px">
            @if(count($facts))
                <section class="sp-provider__panel">
                    <h2>در یک نگاه</h2>
                    <div class="sp-provider__facts">
                        @foreach($facts as $fact)
                            <div class="sp-provider__fact">
                                <span>{{ $fact['label'] }}</span>
                                <strong class="{{ !empty($fact['num']) ? 'num' : '' }}">{{ $fact['value'] }}</strong>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="sp-provider__panel">
                <h2>درباره {{ $roleLabel }}</h2>
                <p>{{ $about }}</p>
            </section>

            {{ $slot }}
        </div>

        <aside class="sp-provider__aside">
            <div class="sp-provider__panel sp-provider__cta">
                <h2 style="margin:0">{{ $bookLabel }}</h2>
                <p class="sp-provider__cta-note">{{ $bookHint }}</p>
                <a class="sp-btn sp-btn--primary" href="{{ $bookUrl }}">
                    <i class="far fa-calendar-check" aria-hidden="true"></i>
                    شروع رزرو
                </a>
            </div>
            @isset($aside)
                <div class="sp-provider__panel">
                    {{ $aside }}
                </div>
            @endisset
        </aside>
    </div>
</div>