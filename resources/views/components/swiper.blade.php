@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
    use App\Helpers\GenralHelpers;
@endphp

<div id="{{ $id }}" class="{{ $type === 'card-service' ? '' : 'swiper-container' }}">
    @if($type === 'card-service')
        @php $chunks = $items->chunk(3); @endphp
        @foreach($chunks->take(2) as $chunk)
            <div class="row d-flex justify-content-center mb-4">
                @foreach($chunk as $item)
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <a href="{{ $item->route_link ?? '#' }}" class="text-decoration-none">
                            <div class="card bg-white align-items-center">
                                @php $imagePath = $item->image ?? $item->profile_picture; @endphp

                                <img src="{{ asset($imagePath ?? 'images/default-user.png') }}"
                                     class="img-fluid rounded-circle h-50 w-50 my-4 object-fit-cover"
                                     alt="{{ $item->title ?? $item->name }}">

                                <x-custom-divider></x-custom-divider>
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-bolder">{{ $item->title ?? $item->name }}</h5>
                                    @if($enabledDesc && ($item->description ?? $item->bio))
                                        <p>{{ $item->description ?? $item->bio }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endforeach

    @elseif($type === 'bests')
        <div class="swiper-wrapper">
            @foreach($items as $item)
                @php
                    $imagePath = $item->profile_picture ?? $item->photo ?? 'images/default-user.png';
                    $role = optional($item->roles->first())->name ?? 'کاربر';
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

                <div class="swiper-slide">
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
                                    <button class="btn btn-outline-secondary w-50">{{ $roleLabel }}</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination mt-3"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    @endif
</div>

@if($type !== 'card-service')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('#{{ $id }}', {
                loop: false,
                slidesPerView: 1,
                spaceBetween: 10,
                breakpoints: {
                    576: { slidesPerView: {{ $slidesPerView ?? 2 }}, spaceBetween: 20 },
                    768: { slidesPerView: {{ $slidesPerView ?? 3 }}, spaceBetween: 20 },
                    992: { slidesPerView: {{ $slidesPerView ?? 4 }}, spaceBetween: 20 },
                    1200: { slidesPerView: {{ $slidesPerView ?? 5 }}, spaceBetween: 20 }
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


@if($type !== 'card-service')
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
