@php
    // Generic fallbacks, use null-safe operator and ?? fallback
    $imageUrl = $item->image_url ?? $item->profile_picture ?? asset('images/dogPicture.jpg');
    $name = $item->name ?? ($item->title ?? 'بدون نام');
    $cityName = $item->city->name ?? 'نامشخص';
    $specialtyName = $item->specialty->name ?? 'بدون تخصص';
    $description = $item->description ?? ($item->bio ?? '');
    $ownerName = $item->owner->name ?? ($item->user->name ?? 'مالک نامشخص');
    // Use passed route or fallback to dog supply show
    $showRoute = $showRoute ?? 'frontend.dog-supply.show';
@endphp

<div class="card h-100 position-relative">
    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $name }}">

    <div class="card-body d-flex flex-column">
        <h6 class="fw-bold">{{ $name }}</h6>

        @if($description)
            <small class="text-muted d-block mb-2">{{ Str::limit($description, 80) }}</small>
        @endif

        <small class="text-muted d-block">{{ $cityName }} | {{ $specialtyName }}</small>
    </div>

    <a href="{{ route($showRoute, $item->id) }}"
       class="btn btn-primary btn-sm position-absolute start-0 bottom-0 m-2">
        مشاهده
    </a>
</div>
