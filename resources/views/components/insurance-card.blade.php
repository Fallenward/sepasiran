@php
    $name = $item->name ?? 'بیمه';
    $description = $item->description ?? 'پوشش بیمه حیوانات خانگی';
    $imageUrl = method_exists($item, 'logoUrl')
        ? $item->logoUrl()
        : asset('images/demo/hero/hero-03.jpg');

    $fromPrice = null;
    if (isset($item->activePlans) && $item->activePlans->isNotEmpty()) {
        $fromPrice = (int) $item->activePlans->min('price');
    } elseif (isset($item->plans) && $item->plans->isNotEmpty()) {
        $fromPrice = (int) $item->plans->min('price');
    }

    $metaBits = [];
    if (! empty($item->city?->name)) {
        $metaBits[] = $item->city->name;
    }
    if ($item->is_featured ?? false) {
        $metaBits[] = 'ویژه';
    }
    $meta = $metaBits !== [] ? implode(' · ', $metaBits) : \Illuminate\Support\Str::limit(strip_tags($description), 48);
@endphp

<article class="sp-card sp-card--dog">
    <a class="sp-card__link" href="{{ route('frontend.clinic.insurance.show', $item->id) }}">
        <div class="sp-card__media">
            <img src="{{ $imageUrl }}" alt="{{ $name }}" loading="lazy">
        </div>
        <div class="sp-card__body">
            <h3 class="sp-card__title">{{ $name }}</h3>
            <p class="sp-card__meta">{{ $meta }}</p>
            @if($fromPrice)
                <p class="sp-card__price num">از {{ number_format($fromPrice) }} تومان</p>
            @endif
        </div>
    </a>
</article>
