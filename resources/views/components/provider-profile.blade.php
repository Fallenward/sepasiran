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
    'sessionResume' => null,
    'profile' => null,
])
@php
    $avatar = method_exists($provider, 'avatarUrl') ? $provider->avatarUrl() : null;
    if (! $avatar) {
        $avatar = asset('images/demo/dogs/dog-02.jpg');
    }
    $specialties = collect($specialties ?? []);
    $sessionResume = $sessionResume ?? ['total' => 0, 'items' => collect()];
    $profile = $profile ?? \App\Support\ProviderPublicProfile::summarize($provider, (int) ($sessionResume['total'] ?? 0));
    if ($cityName) {
        $profile['city_name'] = $cityName;
    }
    if ($specialties->isNotEmpty() && $profile['specialties']->isEmpty()) {
        $profile['specialties'] = $specialties->map(fn ($s) => is_string($s) ? $s : ($s->name ?? null))->filter()->values();
    }
@endphp
<div class="sp-provider">
    <section class="sp-provider__hero" style="--sp-provider-hero-image: url('{{ asset($heroImage) }}')">
        <div class="sp-provider__hero-inner">
            <img class="sp-provider__avatar" src="{{ $avatar }}" alt="{{ $profile['full_name'] }}">
            <div>
                <p class="sp-provider__kicker">{{ $roleLabel }}</p>
                <h1 class="sp-provider__name">{{ $profile['full_name'] !== '' ? $profile['full_name'] : $roleLabel }}</h1>
                <p class="sp-provider__lede">{{ $lede }}</p>
                <div class="sp-provider__chips">
                    @if($profile['is_verified'])
                        <span class="sp-provider__chip is-accent"><i class="fas fa-shield-alt" aria-hidden="true"></i> احراز هویت شده</span>
                    @else
                        <span class="sp-provider__chip"><i class="fas fa-user-check" aria-hidden="true"></i> عضو سپاس‌ایران</span>
                    @endif
                    @if($profile['city_name'])
                        <span class="sp-provider__chip"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $profile['city_name'] }}</span>
                    @endif
                    @foreach($profile['specialties']->take(4) as $spec)
                        <span class="sp-provider__chip">{{ $spec }}</span>
                    @endforeach
                </div>
                <div style="margin-top:12px">
                    <x-provider-rating :avg="$profile['rating_avg']" :count="$profile['rating_count']" />
                </div>
            </div>
        </div>
    </section>

    <div class="sp-provider__layout">
        <div style="display:grid;gap:18px">
            <section class="sp-provider__panel sp-provider__info-card">
                <h2>اطلاعات {{ $roleLabel }}</h2>
                <div class="sp-provider__info-grid">
                    <div class="sp-provider__info-main">
                        <img class="sp-provider__info-avatar" src="{{ $avatar }}" alt="{{ $profile['full_name'] }}">
                        <div>
                            <strong class="sp-provider__info-name">{{ $profile['full_name'] }}</strong>
                            <p class="sp-provider__info-role">{{ $roleLabel }}@if($profile['city_name']) · {{ $profile['city_name'] }}@endif</p>
                            <x-provider-rating :avg="$profile['rating_avg']" :count="$profile['rating_count']" />
                        </div>
                    </div>
                    @if(count($facts))
                        <div class="sp-provider__facts">
                            @foreach($facts as $fact)
                                <div class="sp-provider__fact">
                                    <span>{{ $fact['label'] }}</span>
                                    <strong class="{{ !empty($fact['num']) ? 'num' : '' }}">{{ $fact['value'] }}</strong>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @if($profile['specialties']->isNotEmpty())
                    <div class="sp-provider__tags" style="margin-top:14px">
                        @foreach($profile['specialties'] as $spec)
                            <span class="sp-provider__tag">{{ $spec }}</span>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="sp-provider__panel">
                <h2>درباره {{ $roleLabel }}</h2>
                <p>{{ $about }}</p>
            </section>

            {{ $slot }}

            <x-provider-session-resume :resume="$sessionResume" :role-label="$roleLabel" />
        </div>

        <aside class="sp-provider__aside">
            <div class="sp-provider__panel sp-provider__identity">
                <img class="sp-provider__info-avatar" src="{{ $avatar }}" alt="{{ $profile['full_name'] }}">
                <strong>{{ $profile['full_name'] }}</strong>
                <p class="sp-provider__info-role">{{ $roleLabel }}</p>
                <x-provider-rating :avg="$profile['rating_avg']" :count="$profile['rating_count']" />
                @if($profile['city_name'])
                    <p class="sp-provider__cta-note" style="margin:8px 0 0"><i class="fas fa-map-marker-alt"></i> {{ $profile['city_name'] }}</p>
                @endif
                @if($profile['specialties']->isNotEmpty())
                    <div class="sp-provider__tags" style="margin-top:10px">
                        @foreach($profile['specialties']->take(6) as $spec)
                            <span class="sp-provider__tag">{{ $spec }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
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