@php
    $imageUrl = $item->profile_picture
        ? (str_starts_with($item->profile_picture, 'http') ? $item->profile_picture : asset('storage/' . ltrim($item->profile_picture, '/')))
        : asset('images/demo/dogs/dog-03.jpg');
    $fullName = trim(($item->name ?? '') . ' ' . ($item->family ?? ''));
    if ($fullName === '') { $fullName = 'پرورش‌دهنده'; }
    $cityName = $item->city->name ?? 'نامشخص';
    $bio = $item->bio ?? 'پرورش‌دهنده معتبر سپاسیران';
@endphp
<article class="sp-card sp-card--person">
    <a class="sp-card__link" href="{{ route('frontend.breeders.show', $item->id) }}">
        <div class="sp-card__media">
            <img src="{{ $imageUrl }}" alt="{{ $fullName }}" loading="lazy">
        </div>
        <div class="sp-card__body">
            <h3 class="sp-card__title">{{ $fullName }}</h3>
            <p class="sp-card__meta">{{ $cityName }}</p>
            <p class="sp-card__meta">{{ \Illuminate\Support\Str::limit(strip_tags($bio), 60) }}</p>
        </div>
    </a>
</article>