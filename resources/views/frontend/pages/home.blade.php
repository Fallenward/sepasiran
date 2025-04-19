@extends('layouts.app')

@section('title', $title)

@section('content')

    {{-- ======================= Sliders Section ======================= --}}
    <section class="Slider-section">
        <div class="col-12">
            <x-swiper :id="'carouselSliderIndicators'" :items="$sliders" :type="'image-only'" :slidesPerView="1" :ride="false"/>
        </div>
    </section>
    {{-- ======================= End of Sliders Section ======================= --}}

    {{-- ======================= Courses Section ======================= --}}
    <section class="Courses-section">
        <div class="mt-107"></div>

        <div class="container">
            <x-section-header title="اخبار" buttonText="مشاهده همه" link="#"/>
        </div>

        <div class="mt-107"></div>

{{--        <div class="container-fluid bg-gradient-custom">--}}
{{--            <div class="col-12 upper-box">--}}
{{--                <x-swiper :id="'coursesFirstCarousel'" :items="$courses" :slidesPerView="5" :type="'card'"--}}
{{--                          :ride="false" :enabledDesc="false" :enabledLink="true" :viewLink="'frontend.courses.show'"/>--}}
{{--            </div>--}}
{{--        </div>--}}
    </section>
    {{-- ======================= End of Courses Section ======================= --}}
@endsection
