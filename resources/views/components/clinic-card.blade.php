@php
    $summary = \App\Support\ProviderPublicProfile::summarize($item, (int) ($item->completed_sessions_count ?? 0));
    $imageUrl = method_exists($item, 'avatarUrl') && $item->avatarUrl()
        ? $item->avatarUrl()
        : asset('images/demo/blogs/blog-04.jpg');
@endphp
<article class="sp-card sp-card--person sp-card--provider">
    <a class="sp-card__link" href="{{ route('frontend.clinics.show', $item->id) }}">
        <div class="sp-card__media">
            <img src="{{ $imageUrl }}" alt="{{ $summary['full_name'] }}" loading="lazy">
        </div>
        <div class="sp-card__body">
            <div class="sp-card__identity">
                <h3 class="sp-card__title">{{ $summary['full_name'] }}</h3>
                <span class="sp-card__role">کلینیک</span>
            </div>
            <x-provider-rating :avg="$summary['rating_avg']" :count="$summary['rating_count']" />
            <p class="sp-card__meta">
                @if($summary['city_name'])
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $summary['city_name'] }}
                @endif
                @if($summary['is_verified'])
                    · <i class="fas fa-shield-alt" aria-hidden="true"></i> احراز هویت
                @endif
            </p>
            @if($summary['specialties']->isNotEmpty())
                <div class="sp-card__tags">
                    @foreach($summary['specialties']->take(3) as $spec)
                        <span class="sp-card__tag">{{ $spec }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </a>
</article>