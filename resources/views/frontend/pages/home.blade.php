@extends('layouts.app')

@section('title', $title)

@section('content')

    {{-- ======================= Sliders Section ======================= --}}
    <section class="Slider-section">
        <div class="col-12">
            <x-swiper :id="'carouselSliderIndicators'" :items="$sliders" :type="'image-only'" :slidesPerView="1"
                      :ride="false"/>
        </div>
    </section>
    {{-- ======================= End of Sliders Section ======================= --}}




    {{-- ======================= Blogs Section ======================= --}}
<section class="blogs-section">
    <div class="container">
        <x-section-header title="اخبار" buttonText="مشاهده همه" link="{{ route('frontend.news.index') }}"/>
    </div>

    <div class="mt-107"></div>

    <div class="container">
        <div class="col-12 upper-box">
            <x-blogs-slider
                :id="'blogsCarousel'"
                :items="$blogs"
                :slidesPerView="3"
                :type="'card-with-date'"
                :ride="false"
                :enabledDesc="true"
                :enabledLink="true"
                :viewLink="'frontend.frontend.blogs.show'"/>
        </div>
    </div>
</section>

    {{-- ======================= End of Blogs Section ======================= --}}


    {{-- ======================= Services Section ======================= --}}
    <section class="services-section">


        <div class="container">
            <x-section-header title="خدمات" buttonText="مشاهده همه" link="#"/>
        </div>

        <div class="mt-107"></div>

        <div class="container">
            <div class="col-12 upper-box">
                <x-swiper :id="'servicesCarousel'" :items="$services" :slidesPerView="6" :type="'card-service'"
                          :ride="false" :enabledDesc="false" :enabledLink="true" :viewLink="'frontend.services.show'"/>
            </div>
        </div>
    </section>
    {{-- ======================= End of Services Section ======================= --}}

    {{-- ======================= Statistics Section ======================= --}}
    <section class="statistics-section">

        <div class="container">
            <div class="row g-4">
                @foreach($statistics as $item)
                    <div class="col-6 col-md-6 col-lg-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <p class="card-text-counter">{{ $item['counter'] }}<i class="fa fa-plus"></i></p>
                                <h5 class="card-title-counter">{{ $item['title'] }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    {{-- ======================= End of Statistics Section ======================= --}}


    {{-- ======================= Coverage Section ======================= --}}

    {{-- ======================= End of Coverage Section ======================= --}}
    <div class="mt-107"></div>
    <section class="coverage-section">
        <div class="container-custom">
            <div class="text-column">
                <h2 class="mb-3 text-white">پناهگاه</h2>
                <x-title-divider></x-title-divider>
                <p class="my-4 text-white">
                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است...
                </p>
                <a href="{{ route('frontend.shelter.index') }}" class="btn btn-light mb-4">مشاهده پناهگاه</a>
            </div>
            <div class="image-column">
                <div class="image-wrapper">
                    <img src="{{ asset('images/shelther.png') }}" alt="Shelter" class="dog-image">
                </div>
            </div>
        </div>
    </section>


    {{-- ======================= Best Section ======================= --}}
    <section class="best-section">

        <div class="mt-107"></div>
        <div class="container">
            <x-section-header title="برترین ها" buttonText="مشاهده همه" link="#"/>
        </div>

        <div class="mt-107"></div>

        <div class="container">
            <div class="col-12 upper-box">
                <x-swiper :id="'blogsCarousel'" :items="$bests" :slidesPerView="3" :type="'bests'"
                          :ride="false" :enabledDesc="false" :enabledLink="true" :viewLink="'frontend.blog.show'"/>
            </div>
        </div>
    </section>
    {{-- ======================= End of Best Section ======================= --}}

    <div class="mt-5 text-center">
        <h5 class="fw-bold mb-4">پیوند به سایت‌های دیگر</h5>
        <div class="d-flex justify-content-center gap-4 custom-link-area">
            <a href="#" class="card  custom-link-card p-4 text-decoration-none">
                <img src="{{ asset('images/rahbari.png') }}" alt="مقام معظم رهبری" class="img-fluid mx-2 rounded-circle" width="75"
                     height="50">
                <span class="ms-3 custom-text">پایگاه اطلاع رسانی دفتر مقام معظم رهبری</span>
            </a>
            <a href="#" class="card custom-link-card p-4 text-decoration-none">
                <img src="{{ asset('images/dolat.png') }}" alt="دولت" class="img-fluid mx-2 rounded-circle" width="75"
                     height="75">
                <span class="ms-3 custom-text">پایگاه اطلاع رسانی دولت</span>
            </a>
            <a href="#" class="card custom-link-card p-4 text-decoration-none">
                <img src="{{ asset('images/paygahaha.png') }}" alt="ریاست جمهوری" class="img-fluid mx-2 rounded-circle" width="75"
                     height="75">
                <span class="ms-3 custom-text">پایگاه اطلاع رسانی ریاست جمهوری</span>
            </a>

        </div>
    </div>

@endsection
