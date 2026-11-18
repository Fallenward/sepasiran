@php
    $imageUrl = $item->image_url
        ? asset('storage/' . $item->image_url)
        : ($item->profile_picture ?? asset('images/dogPicture.jpg'));

    $name = $item->name ?? ($item->title ?? 'بدون نام');
    $cityName = $item->city->name ?? 'نامشخص';
    $breedName = $item->breed->name ?? ($item->specialty->name ?? 'نژاد نامشخص');
    $showRoute = $showRoute ?? 'frontend.dog-supply.show';
@endphp

<article class="tile h-100">
    <a class="tile-link" href="{{ route($showRoute, $item->id) }}">
        <div class="tile__media">
            <img src="{{ $imageUrl }}" alt="{{ $name }}">
        </div>
        <div class="tile__body">
            <h3>{{ $name }}</h3>
            <div class="tile__meta">
                <span>{{ $breedName }} · {{ $cityName }}</span>
                @if(!empty($item->age))
                    <span class="tile__price num">{{ $item->age }} ماه</span>
                @endif
            </div>
        </div>
    </a>
</article>
