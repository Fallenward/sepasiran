@php
    $rawImage = $item->image ?? $item->image_url ?? null;
    if ($rawImage) {
        $imageUrl = str_starts_with($rawImage, 'http')
            ? $rawImage
            : (str_starts_with($rawImage, 'images/')
                ? asset($rawImage)
                : asset('storage/' . ltrim($rawImage, '/')));
    } else {
        $imageUrl = asset('images/demo/dogs/dog-01.jpg');
    }

    $name = $item->name ?? ($item->title ?? 'بدون نام');
    $cityName = $item->city->name ?? 'تهران';
    $breedName = $item->breed->name ?? ($item->breed ?? 'نژاد ترکیبی');
    $showRoute = $showRoute ?? 'frontend.dog-supply.show';
@endphp

<article class="sp-card sp-card--dog">
    <a class="sp-card__link" href="{{ route($showRoute, $item->id) }}">
        <div class="sp-card__media">
            <img src="{{ $imageUrl }}" alt="{{ $name }}" loading="lazy">
        </div>
        <div class="sp-card__body">
            <h3 class="sp-card__title">{{ $name }}</h3>
            <p class="sp-card__meta">{{ $breedName }} · {{ $cityName }}</p>
            @if(!empty($item->age))
                <p class="sp-card__price num">{{ $item->age }} ماهه</p>
            @endif
        </div>
    </a>
</article>