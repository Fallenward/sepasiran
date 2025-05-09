@php
    // Get image URL if available or fallback
    $imageUrl = $item->image_url ?? asset('images/dogPicture.jpg');

    // Fallback for name
    $name = $item->name ?? 'بدون نام';

    // Fallback for city and specialty
    $cityName = $item->city->name ?? 'نامشخص';
    $specialtyName = $item->specialty->name ?? 'بدون تخصص';

    // Description fallback
    $description = $item->description ?? '';
@endphp

<div class="card h-100">
    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $name }}">
    <div class="card-body d-flex flex-column">
        <h6 class="fw-bold">{{ $name }}</h6>

        @if($description)
            <small class="text-muted d-block mb-2">{{ Str::limit($description, 80) }}</small>
        @endif

        <small class="text-muted">{{ $cityName }} | {{ $specialtyName }}</small>

        {{-- Optional button for viewing profile --}}
        @if(request()->is('coaches') || request()->is('clinics') || request()->is('dogs'))
            <a href="#" class="btn btn-outline-primary btn-sm mt-2">مشاهده پروفایل</a>
        @endif
    </div>
</div>
