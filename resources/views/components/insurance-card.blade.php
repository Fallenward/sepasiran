@php
    $raw = $item->logo ?? null;
    $imageUrl = $raw
        ? (str_starts_with($raw, 'http') ? $raw : (str_starts_with($raw, 'images/') ? asset($raw) : asset('storage/' . ltrim($raw, '/'))))
        : asset('images/demo/hero/hero-03.jpg');
    $name = $item->name ?? 'بیمه';
    $description = $item->description ?? '';
    $cityName = $item->city->name ?? 'سراسر کشور';
@endphp
<article class="sp-card sp-card--person">
    <a class="sp-card__link" href="{{ route('frontend.clinic.insurance.show', $item->id) }}">
        <div class="sp-card__media">
            <img src="{{ $imageUrl }}" alt="{{ $name }}" loading="lazy">
        </div>
        <div class="sp-card__body">
            <h3 class="sp-card__title">{{ $name }}</h3>
            <p class="sp-card__meta">{{ $cityName }}</p>
            @if($description)
                <p class="sp-card__meta">{{ \Illuminate\Support\Str::limit(strip_tags($description), 55) }}</p>
            @endif
        </div>
    </a>
</article>