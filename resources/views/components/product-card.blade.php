@php
    // Image URL: product thumbnail or fallback
    $imageUrl = $item->thumbnail
        ? asset('storage/products/' . $item->thumbnail)
        : asset('images/default-product.jpg');

    // Title and description
    $name = $item->title ?? 'بدون نام';
    $description = $item->description ?? '';

    // Owner (optional)
    $owner = $item->user ?? null;
    $ownerName = $owner->name ?? 'مالک نامشخص';
    $ownerProfilePicture = $owner->profile_picture ?? $owner->photo ?? null;
    $ownerImage = $ownerProfilePicture
        ? asset('storage/' . $ownerProfilePicture)
        : asset('images/default-user.png');

    // Route for show page (pass from parent or fallback)
    $showRoute = $showRoute ?? 'frontend.products.show';
@endphp

<div class="card h-100 position-relative shadow-sm border-0">
    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $name }}" style="object-fit: cover; height: 200px; display: block;">

    <div class="card-body d-flex flex-column pb-2">
        <h6 class="fw-bold mb-1">{{ $name }}</h6>

        @if($description)
            <small class="text-muted d-block mb-2">{{ Str::limit(strip_tags($description), 80) }}</small>
        @endif
    </div>

    @if($owner)
        <div class="d-flex align-items-center gap-2 px-3 pb-1">
            <img src="{{ $ownerImage }}" alt="مالک" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
            <small class="text-muted mb-0">{{ $ownerName }}</small>
        </div>
    @endif
        <a href="{{ route($showRoute, ['id' => $item->id]) }}"
        class="btn btn-primary btn-sm position-absolute start-0 bottom-0 m-2"
        style="z-index: 10;">
            مشاهده
        </a>

</div>
