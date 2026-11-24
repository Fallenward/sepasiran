@php
    $raw = $item->thumbnail ?? null;
    if ($raw) {
        $imageUrl = str_starts_with($raw, 'http')
            ? $raw
            : (str_starts_with($raw, 'images/')
                ? asset($raw)
                : asset('storage/products/' . ltrim($raw, '/')));
    } else {
        $imageUrl = asset('images/demo/products/product-01.jpg');
    }

    $name = $item->title ?? 'بدون نام';
    $price = $item->discount_price ?: ($item->price ?? null);
    $showRoute = $showRoute ?? 'frontend.products.show';
@endphp

<article class="sp-card sp-card--product">
    <a class="sp-card__link" href="{{ route($showRoute, ['id' => $item->id]) }}">
        <div class="sp-card__media sp-card__media--square">
            <img src="{{ $imageUrl }}" alt="{{ $name }}" loading="lazy">
        </div>
        <div class="sp-card__body">
            <h3 class="sp-card__title">{{ $name }}</h3>
            @if($price !== null)
                <p class="sp-card__price num">{{ number_format((float) $price) }} تومان</p>
            @endif
        </div>
    </a>
</article>