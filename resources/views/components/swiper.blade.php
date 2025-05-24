@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
    use App\Helpers\GenralHelpers;

    $viewLink = $viewLink ?? null;
@endphp

<div id="{{ $id }}" class="{{ $type === 'card-service' || $type === 'bests' ? '' : 'swiper-container' }}">
    @if($type === 'card-service')
        @php
            $chunks = $items->chunk(3);
        @endphp
        @foreach($chunks->take(2) as $chunk)
            <div class="row d-flex justify-content-center mb-4">
                @foreach($chunk as $item)
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <a href="{{ $item->route_link ?? ($viewLink ? route($viewLink, ['id' => $item->id]) : '#') }}" class="text-decoration-none">
                            <div class="card bg-white align-items-center">
                                @if($item->image)
                                    <img src="{{ asset($item->image) }}" class="img-fluid h-50 w-50 my-4" alt="{{ $item->title }}">
                                @endif
                                <x-custom-divider></x-custom-divider>
                                <div class="card-body">
                                    <h5 class="card-title fw-bolder">{{ $item->title }}</h5>
                                    @if($enabledDesc ?? false)
                                        <p class="text-center">{{ $item->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endforeach

    @elseif($type === 'bests')
        @php
            $chunks = $items->chunk(3); // Same layout as card-service: 3 per row
        @endphp

        @foreach($chunks->take(2) as $chunk)
            <div class="row d-flex justify-content-center mb-4">
                @foreach($chunk as $item)
                    @php
                        $imagePath = $item->profile_picture ?? $item->photo ?? 'images/default-user.png';
                        $role = optional($item->roles->first())->name ?? 'کاربر'; // Get first role name or default

                        // Set role-based route and label
                        $route = '#';
                        $roleLabel = 'نقش ندارد';

                        if ($role === 'coach') {
                            $route = route('frontend.coaches.show', $item->id);
                            $roleLabel = 'مربی';
                        } elseif ($role === 'clinic') {
                            $route = route('frontend.clinics.index', $item->id);
                            $roleLabel = 'کلینیک';
                        } elseif ($role === 'breeder') {
                            $route = route('frontend.breeders.show', $item->id);
                            $roleLabel = 'پرورش‌دهنده';
                        }
                    @endphp

                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="card bg-white d-flex flex-column h-100 p-3">
                            <img src="{{ asset($imagePath) }}"
                                 class="img-fluid rounded-circle h-50 w-50 my-4 object-fit-cover"
                                 alt="{{ $item->name }}">

                            <x-custom-divider></x-custom-divider>

                            <div class="card-body d-flex flex-column flex-grow-1 text-center">
                                <h5 class="card-title fw-bold">{{ $item->name }} {{ $item->family ?? '' }}</h5>

                                @if($enabledDesc && $item->bio)
                                    <p class="text-center">{{ GenralHelpers::limitTextToTwoLines($item->bio) }}</p>
                                @else
                                    <p class="text-center text-muted">بدون بیوگرافی</p>
                                @endif

                                <div class="d-flex justify-content-center my-2">
                                    <span>
                                        <i class="fa fa-star star-color"></i>
                                        <i class="fa fa-star star-color"></i>
                                        <i class="fa fa-star star-color"></i>
                                        <i class="fa fa-star star-color"></i>
                                        <i class="fa fa-star star-color"></i>
                                        5
                                    </span>
                                </div>

                                @if($enabledLink)
                                    <div class="mt-auto d-flex justify-content-between gap-2">
                                        <a href="{{ $route }}" class="btn btn-primary w-50">مشاهده</a>
                                    <button class="btn btn-custom-green w-50">
                                        {{ $roleLabel }}
                                    </button>

                                    </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

    @else
        <div class="swiper-wrapper">
            @foreach($items as $item)
                <div class="swiper-slide">
                    @if($type === 'image-only')
                        <img src="{{ asset($item->image) }}" class="d-block w-100" alt="{{ $item->title }}">
                    @elseif($type === 'card')
                        <div class="row d-flex justify-content-center">
                            <div class="col-12">
                                <div class="card custom-card">
                                    @if($item->image)
                                        <img src="{{ asset($item->image) }}" class="card-img-top" alt="{{ $item->title }}">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $item->title }}</h5>
                                        @if($enabledDesc ?? false)
                                            <p class="text-center">{{ $item->description }}</p>
                                        @endif
                                        @if($enabledLink ?? false)
                                            <a href="{{ $item->route_link ?? ($viewLink ? route($viewLink, ['id' => $item->id]) : '#') }}" class="btn btn-primary">مشاهده</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($type === 'masters-card')
                        <div class="row d-flex justify-content-center">
                            <div class="col-12">
                                @if($item->image)
                                    <img src="{{ asset($item->image) }}" class="card-img-top masters-image-card" alt="{{ $item->title }}">
                                @endif
                                <div class="card custom-card masters-card">
                                    <div class="card-body masters-card-body">
                                        <h5 class="card-title">{{ $item->fullname }}</h5>
                                        @if($enabledDesc ?? false)
                                            <p class="text-center m-0">{{ $item->description }}</p>
                                        @endif
                                        @if($enabledLink ?? false)
                                            <a href="{{ $item->route_link ?? ($viewLink ? route($viewLink, ['id' => $item->id]) : '#') }}" class="btn btn-primary">مشاهده</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($type === 'card-with-date')
                        <div class="row d-flex justify-content-center">
                            <div class="col-12">
                                <div class="card">
                                    @if($item->image)
                                        <img src="{{ asset($item->image) }}" class="card-img-top feature-image-blog" alt="{{ $item->title }}">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $item->title }}</h5>
                                        @if($enabledDesc ?? false)
                                            <p class="text-justify">{{ GenralHelpers::limitTextToTwoLines($item->description) }}</p>
                                        @endif
                                        <div class="d-flex align-items-center">
                                            <span>
                                                <i class="fa fa-star star-color"></i>
                                                <i class="fa fa-star star-color"></i>
                                                <i class="fa fa-star star-color"></i>
                                                <i class="fa fa-star star-color"></i>
                                                <i class="fa fa-star star-color"></i>
                                                5
                                            </span>
                                        </div>
                                        @if($enabledLink ?? false)
                                            <div class="col-12 justify-content-between d-flex align-items-baseline gap-2">
                                                <a href="{{ $item->route_link ?? ($viewLink ? route($viewLink, ['id' => $item->id]) : '#') }}" class="btn btn-primary w-50"> مشاهده پروفایل</a>
                                                <button class="btn btn-light w-50">مربی</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    @endif
</div>

@if($type !== 'card-service' && $type !== 'bests')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('#{{ $id }}', {
                loop: false,
                slidesPerView: 1,
                spaceBetween: 10,
                breakpoints: {
                    576: {
                        slidesPerView: {{ $slidesPerView ?? 2 }},
                        spaceBetween: 20
                    },
                    768: {
                        slidesPerView: {{ $slidesPerView ?? 3 }},
                        spaceBetween: 20
                    },
                    992: {
                        slidesPerView: {{ $slidesPerView ?? 4 }},
                        spaceBetween: 20
                    },
                    1200: {
                        slidesPerView: {{ $slidesPerView ?? 5 }},
                        spaceBetween: 20
                    }
                },
                pagination: {
                    el: ".swiper-pagination",
                    dynamicBullets: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
@endif



<style>
    .btn-custom-green {
    background-color: #f5f5f5 !important;
    border-color: #f5f5f5 !important;
    color: #0f47a1 !important;
}

</style>
