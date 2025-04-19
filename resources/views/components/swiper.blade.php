@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
    use App\Helpers\GenralHelpers;
@endphp

<div id="{{ $id }}" class="swiper-container">
    <!-- Slides -->
    <div class="swiper-wrapper">
        @foreach($items as $item)
            <div class="swiper-slide">
                @if($type === 'image-only')
                    <img src="{{ asset( $item->image) }}" class="d-block w-100" alt="{{ $item->title }}">
                @elseif($type === 'card')
                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <div class="card custom-card">
                                @if($item->image)
                                    <img src="{{ asset( $item->image) }}" class="card-img-top" alt="{{ $item->title }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->title }}</h5>
                                    @if($enabledDesc)
                                        <p class="text-center">{{ $item->description }}</p>
                                    @endif

                                    @if($enabledLink)
                                        <a href="{{route($viewLink,$item->slug)}}" class="btn btn-primary">مشاهده</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($type === 'masters-card')
                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            @if($item->image)
                                <img src="{{ asset( $item->image) }}" class="card-img-top masters-image-card" alt="{{ $item->title }}">
                            @endif
                            <div class="card custom-card masters-card">
                                <div class="card-body masters-card-body">

                                    <h5 class="card-title">{{ $item->fullname }}</h5>
                                    @if($enabledDesc)
                                        <p class="text-center m-0">{{ $item->description }}</p>
                                    @endif
                                    @if($enabledLink)
                                        <a href="{{route($viewLink,$item->slug)}}" class="btn btn-primary">مشاهده</a>
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
                                    <img src="{{ asset($item->image) }}" class="card-img-top" alt="{{ $item->title }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->title }}</h5>
                                    @if($enabledDesc)
                                        <p class="text-justify">{{ GenralHelpers::limitTextToTwoLines($item->description) }}</p>
                                    @endif
                                    @if($enabledLink)
                                        <div class="col-12 justify-content-between d-flex align-items-baseline">
                                            @php
                                                // Ensure $item->created_at is a valid date string
                                                $carbonDate = Carbon::parse($item->created_at);
                                                $persianDate = Jalalian::fromCarbon($carbonDate)->format('j F Y');
                                            @endphp
                                            <div>
                                                <span>{{ $persianDate }} <i class="fa fa-calendar-alt"></i></span>
                                            </div>
                                            @if($enabledLink)
                                                <a href="{{route($viewLink,$item->slug)}}" class="btn btn-primary">مشاهده</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($type === 'comments')
                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <img class="img-fluid w-25" src="{{ asset($item->user->photo) }}">
                                                <div class="d-flex me-2 justify-content-center flex-column">
                                                    <span>{{ $item->user->name }}</span>
                                                    <small class="text-black-50 ">{{$item->user->roles->first()->name}}</small>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span>{{ $item->rate }}  <i class="fa fa-star star-color"></i> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-comment"></div>
                                    @if($enabledDesc)
                                        <p class="text-justify">{{ GenralHelpers::limitTextToTwoLines($item->content, 20) }}</p>
                                    @endif
                                    <div class="col-12 justify-content-between d-flex align-items-baseline">
                                        @php
                                            $carbonDate = Carbon::parse($item->created_at);
                                            $persianDate = Jalalian::fromCarbon($carbonDate)->format('j F Y');
                                        @endphp
                                        <div>
                                            <span>{{ $persianDate }}</span>
                                        </div>
                                    </div>
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiper = new Swiper('#{{ $id }}', {
            // Optional parameters
            loop: false,
            slidesPerView: 1,
            spaceBetween: 10,

            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 576px
                576: {
                    slidesPerView: {{ $slidesPerView ?? 2 }},
                    spaceBetween: 20
                },
                // when window width is >= 768px
                768: {
                    slidesPerView: {{ $slidesPerView ?? 3 }},
                    spaceBetween: 20
                },
                // when window width is >= 992px
                992: {
                    slidesPerView: {{ $slidesPerView ?? 4 }},
                    spaceBetween: 20
                },
                // when window width is >= 1200px
                1200: {
                    slidesPerView: {{ $slidesPerView ?? 5 }},
                    spaceBetween: 20
                }
            },

            // If you want pagination
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
            },

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
</script>
