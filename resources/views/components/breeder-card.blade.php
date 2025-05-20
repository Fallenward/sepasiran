@php
    $imageUrl = $item->profile_picture
        ? asset('storage/' . $item->profile_picture)
        : asset('images/default-breeder.png');
    $fullName = trim($item->name . ' ' . $item->family);
    $cityName = $item->city->name ?? 'نامشخص';
    $bio = $item->bio ?? 'بدون توضیح';
@endphp

<div class="card h-100 position-relative">
    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $fullName }}" style="height: 200px; object-fit: cover;">

    <div class="card-body d-flex flex-column">
        <h5 class="fw-bold">{{ $fullName }}</h5>
        <p class="text-muted flex-grow-1">{{ Str::limit($bio, 100) }}</p>
        <small class="text-muted">{{ $cityName }}</small>
    </div>

    <a href="{{ route('frontend.breeders.show', $item->id) }}"
       class="btn btn-primary btn-sm position-absolute start-0 bottom-0 m-2">
        مشاهده
    </a>
</div>
