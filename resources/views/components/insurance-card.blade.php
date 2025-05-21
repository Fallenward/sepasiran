@php
    $imageUrl = $item->logo ? asset('storage/' . $item->logo) : asset('images/dogPicture.jpg');
    $name = $item->name ?? 'بدون نام';
    $description = $item->description ?? '';
    $cityName = $item->city->name ?? 'نامشخص';

    $showRoute = $routeName ?? 'frontend.clinic.insurance.show';

@endphp

<div class="card h-100 position-relative shadow-sm">
    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $name }}">

    <div class="card-body d-flex flex-column">
        <h6 class="fw-bold mb-2">{{ $name }}</h6>

        @if($description)
            <small class="text-muted d-block mb-3">{{ Str::limit($description, 80) }}</small>
        @endif

        <small class="text-muted d-block">{{ $cityName }}</small>
    </div>

    <a href="{{ route('frontend.clinic.insurance.show', $item->id) }}"
       class="btn btn-primary btn-sm position-absolute start-0 bottom-0 m-2">
        مشاهده
    </a>
</div>
