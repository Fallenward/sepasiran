@php
    $imageUrl = $item->image_url
        ? asset('storage/' . $item->image_url)
        : ($item->profile_picture ?? asset('images/dogPicture.jpg'));

    $name = $item->name ?? ($item->title ?? 'بدون نام');
    $cityName = $item->city->name ?? 'نامشخص';
    $specialtyName = $item->specialty->name ?? 'بدون تخصص';
    $description = $item->description ?? ($item->bio ?? '');
    $owner = $item->owner ?? $item->user;
    $ownerName = $owner->name ?? 'مالک نامشخص';
    $ownerProfilePicture = $owner->profile_picture ?? $owner->photo ?? null;
    $ownerImage = $ownerProfilePicture
        ? asset('storage/' . $ownerProfilePicture)
        : asset('images/default-user.png');

    $showRoute = $showRoute ?? 'frontend.dog-supply.show';
@endphp

<div class="card h-100 position-relative shadow-sm border-0">
    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $name }}" style="object-fit: cover; height: 200px; display: block;">

    <div class="card-body d-flex flex-column pb-2">
        <h6 class="fw-bold mb-1">{{ $name }}</h6>

        <div class="mb-1">
            <small class="text-muted d-block"><strong>شهر:</strong> {{ $cityName }}</small>
            <small class="text-muted d-block"><strong>مالک:</strong> {{ $ownerName }}</small>
            <small class="text-muted d-block"><strong>تخصص:</strong> {{ $specialtyName }}</small>
        </div>

        @if($description)
            <small class="text-muted d-block mb-2">{{ Str::limit(strip_tags($description), 80) }}</small>
        @endif
    </div>

    <div class="d-flex align-items-center gap-2 px-3 pb-1">
        <img src="{{ $ownerImage }}" alt="مالک" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover; border-radius: 50%;">
        <small class="text-muted mb-0">{{ $ownerName }}</small>
    </div>

    <a href="{{ route($showRoute, $item->id) }}"
       class="btn btn-primary btn-sm position-absolute start-0 bottom-0 m-2"
       style="z-index: 10;">
        مشاهده
    </a>
</div>
