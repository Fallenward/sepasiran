@php
    $imageUrl = $item->thumbnail
        ? asset('storage/products/' . $item->thumbnail)
        : asset('images/default-product.jpg');

    $name = $item->title ?? 'بدون نام';
    $description = $item->description ?? '';
    $price = $item->price ?? $item->sale_price ?? null;
    $showRoute = $showRoute ?? 'frontend.products.show';
@endphp

<article class="tile h-100">
    <a class="tile-link" href="{{ route($showRoute, ['id' => $item->id]) }}">
        <div class="tile__media tile__media--square">
            <img src="{{ $imageUrl }}" alt="{{ $name }}">
        </div>
        <div class="tile__body">
            <h3>{{ $name }}</h3>
            <div class="tile__meta">
                <span>{{ Str::limit(strip_tags($description), 40) }}</span>
                @if($price !== null)
                    <span class="tile__price num">{{ number_format((float) $price) }}</span>
                @endif
            </div>
        </div>
    </a>
</article>
