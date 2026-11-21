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
@endphp
<article class="sp-ins-card">
    <a class="sp-ins-card__link" href="{{ route('frontend.clinic.insurance.show', $item->id) }}">
        <div class="sp-ins-card__media">
            <img src="{{ $imageUrl }}" alt="{{ $name }}" loading="lazy">
        </div>
        <div class="sp-ins-card__body">
            <h3 class="sp-ins-card__title">{{ $name }}</h3>
            <p class="sp-ins-card__meta">{{ \Illuminate\Support\Str::limit(strip_tags($description), 80) }}</p>
            @if($fromPrice)
                <p class="sp-ins-card__price">از <span class="num">{{ number_format($fromPrice) }}</span> تومان</p>
            @else
                <p class="sp-ins-card__cta">مشاهده طرح‌ها</p>
            @endif
        </div>
    </a>
</article>
