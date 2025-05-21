
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
    use App\Helpers\GenralHelpers;
@endphp

<div id="{{ $id }}" class="{{ $type === 'card-service' ? '' : 'swiper-container' }}">
    @if($type === 'card-service')
        @php
            $chunks = $items->chunk(3);
        @endphp
        @foreach($chunks->take(2) as $chunk)
            <div class="row d-flex justify-content-center mb-4">
                @foreach($chunk as $item)

                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <a href="{{ $item->route_link }}" class="text-decoration-none">
                            <div class="card bg-white align-items-center">
                                @if($item->image)
                                    <img src="{{ asset($item->image) }}" class="img-fluid h-50 w-50 my-4" alt="{{ $item->title }}">
                                @endif
                                <x-custom-divider></x-custom-divider>
                                <div class="card-body">
                                    <h5 class="card-title fw-bolder">{{ $item->title }}</h5>
                                    @if($enabledDesc)
                                        <p class="text-center">{{ $item->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
</div>

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
