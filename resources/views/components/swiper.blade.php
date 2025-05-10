
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
    use App\Helpers\GenralHelpers;
@endphp

<div id="{{ $id }}" class="{{ $type === 'card-service' ? '' : 'swiper-container' }}">
    @if($type === 'card-service')
        <!-- Grid layout for card-service -->
        @php
            // Chunk items into 2 rows with 3 items per row (or adjust based on items count)
            $chunks = $items->chunk(3); // 3 items per row
        @endphp
        @foreach($chunks->take(2) as $chunk)
            <!-- Limit to 2 rows -->
            <div class="row d-flex justify-content-center mb-4">
                @foreach($chunk as $item)

                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <a href="{{ route($viewLink, $item->slug) }}" class="text-decoration-none">
                            <!-- Bootstrap columns: 1 per row on small, 2 on medium, 3 on large -->
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
    @else
        <!-- Swiper for other types -->
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
                                        <img src="{{ asset($item->image) }}" class="card-img-top"
                                             alt="{{ $item->title }}">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $item->title }}</h5>
                                        @if($enabledDesc)
                                            <p class="text-center">{{ $item->description }}</p>
                                        @endif
                                        @if($enabledLink)
                                            <a href="{{ route($viewLink, $item->slug) }}"
                                               class="btn btn-primary">مشاهده</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($type === 'masters-card')
                        <div class="row d-flex justify-content-center">
                            <div class="col-12">
                                @if($item->image)
                                    <img src="{{ asset($item->image) }}" class="card-img-top masters-image-card"
                                         alt="{{ $item->title }}">
                                @endif
                                <div class="card custom-card masters-card">
                                    <div class="card-body masters-card-body">
                                        <h5 class="card-title">{{ $item->fullname }}</h5>
                                        @if($enabledDesc)
                                            <p class="text-center m-0">{{ $item->description }}</p>
                                        @endif
                                        @if($enabledLink)
                                            <a href="{{ route($viewLink, $item->slug) }}"
                                               class="btn btn-primary">مشاهده</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($type === 'card-with-date')
                    <x-blogs-slider :items="$blogs" :enabledDesc="true" :enabledLink="true" viewLink="blogs.show" />

                    @elseif($type === 'bests')
                        <div class="row d-flex justify-content-center">
                            <div class="col-12">
                                <div class="card">
                                    @if($item->image)
                                        <img src="{{ asset($item->image) }}" class="card-img-top feature-image-blog"
                                             alt="{{ $item->title }}">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $item->title }}</h5>
                                        @if($enabledDesc)
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
                                        @if($enabledLink)
                                            <div class="col-12 justify-content-between d-flex align-items-baseline gap-2">
                                                @if($enabledLink)
                                                    <a href="{{ route($viewLink, $item->slug) }}"
                                                       class="btn btn-primary w-50">مشاهده</a>
                                                @endif
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

        <!-- Pagination -->
        <div class="swiper-pagination"></div>

        <!-- Navigation buttons -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
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
