@php
    $name = $item->name ?? 'بیمه';
    $description = $item->description ?? '';
    $imageUrl = method_exists($item, 'logoUrl') ? $item->logoUrl() : asset('images/demo/hero/hero-03.jpg');
    $fromPrice = null;
    if (isset($item->activePlans) && $item->activePlans->isNotEmpty()) {
        $fromPrice = (int) $item->activePlans->min('price');
    }
@endphp
<a class="sp-ins-index-m__item" href="{{ route('frontend.clinic.insurance.show', $item->id) }}">
    <span class="sp-ins-index-m__thumb" style="background-image:url('{{ $imageUrl }}')"></span>
    <span class="sp-ins-index-m__meta">
        <strong>{{ $name }}</strong>
        <small>{{ \Illuminate\Support\Str::limit(strip_tags($description), 64) }}</small>
        @if($fromPrice)
            <em>از <span class="num">{{ number_format($fromPrice) }}</span> ت</em>
        @endif
    </span>
</a>
