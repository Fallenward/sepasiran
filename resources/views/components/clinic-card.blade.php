@php
    // Fallbacks
    $imageUrl = $item->profile_picture ? asset('storage/' . $item->profile_picture) : asset('images/default-profile.jpg');
    $name = $item->name ?? 'بدون نام';
    $cityName = $item->city->name ?? 'نامشخص';
    $specialtyName = $item->specialty->name ?? 'بدون تخصص';
    $description = $item->bio ?? '';
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

    <a href="{{ route('frontend.clinics.index', $item->id) }}"
       class="btn btn-outline-primary btn-sm position-absolute start-0 bottom-0 m-2">
        مشاهده
    </a>
</div>
