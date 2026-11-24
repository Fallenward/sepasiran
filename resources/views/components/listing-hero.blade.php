@props([
    'title',
    'lede' => null,
    'icon' => 'spark',
    'image' => 'images/demo/hero/hero-01.jpg',
    'crumbs' => [],
])
<section class="sp-listing-hero">
    <div class="sp-listing-hero__bg" aria-hidden="true">
        <img src="{{ asset($image) }}" alt="">
    </div>
    <div class="wrap sp-listing-hero__inner">
        @if(count($crumbs))
            <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
                @foreach($crumbs as $i => $crumb)
                    @if($i > 0)<span class="sp-breadcrumb__sep">/</span>@endif
                    @if(!empty($crumb['route']))
                        <a href="{{ $crumb['route'] }}">{{ $crumb['label'] }}</a>
                    @else
                        <span>{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif
        <div class="sp-listing-hero__title-row">
            <span class="sp-section-head__icon" aria-hidden="true"><x-sp-icon :name="$icon" /></span>
            <h1>{{ $title }}</h1>
        </div>
        @if($lede)
            <p class="sp-listing-hero__lede">{{ $lede }}</p>
        @endif
    </div>
</section>