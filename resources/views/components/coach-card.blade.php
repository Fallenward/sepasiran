@php
    $raw = $item->profile_picture ?? null;
    $imageUrl = $raw
        ? (str_starts_with($raw, 'http') ? $raw : (str_starts_with($raw, 'images/') ? asset($raw) : asset('storage/' . ltrim($raw, '/'))))
        : asset('images/demo/products/product-05.jpg');
    $name = $item->name ?? 'مربی';
    $cityName = $item->city->name ?? 'نامشخص';
    $specialtyRel = $item->speciality ?? ($item->specialty ?? null);
    if ($specialtyRel instanceof \Illuminate\Support\Collection) {
        $specialtyName = optional($specialtyRel->first())->name ?? 'تخصص عمومی';
    } else {
        $specialtyName = optional($specialtyRel)->name ?? 'تخصص عمومی';
    }
@endphp
<article class="sp-card sp-card--person">
    <a class="sp-card__link" href="{{ route('frontend.coaches.show', $item->id) }}">
        <div class="sp-card__media">
            <img src="{{ $imageUrl }}" alt="{{ $name }}" loading="lazy">
        </div>
        <div class="sp-card__body">
            <h3 class="sp-card__title">{{ $name }}</h3>
            <p class="sp-card__meta">{{ $specialtyName }} · {{ $cityName }}</p>
        </div>
    </a>
</article>