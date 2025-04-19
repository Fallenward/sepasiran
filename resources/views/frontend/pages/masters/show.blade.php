@extends('frontend.layouts.app')

@section('title', $title)

@section('content')
    <!-- Section 1: Header -->
    <div class="container">
        <div class="col-12">
            {{ Breadcrumbs::render('masters.show',$master) }}
        </div>
    </div>

    <div class="container">
        <div class="col-12 col-md-6">
            <h2>{{$title}}</h2>
            <x-title-divider></x-title-divider>
        </div>
        <div class="normal-divider"></div>
        <div class="row d-flex align-items-center">
            <!-- Vertical Divider (Hidden on Small Screens) -->
            <div class="col-auto d-none d-md-block">
                <x-line-divider-vertical></x-line-divider-vertical>
            </div>
            <div class="col-auto d-block d-md-none">
                <x-line-divider></x-line-divider>
            </div>
            <!-- Text Column -->
            <div class="col-12 col-md-10 faq-text">
                <p>{!! $master->description !!}</p>
            </div>
        </div>
    </div>
    <div class="normal-divider"></div>

    <div class="container my-5">
        <div class="row row-gap-3"> <!-- اینجا gx-4 اضافه شد -->
            <!-- Left Column (Content) -->
            <div class="col-lg-4 col-12 text-center card shadow p-4">
                <img src="{{asset('storage/'.$master->image)}}" alt="{{$master->title}}" class="img-fluid rounded mb-3">
                <div class="d-flex justify-content-center gap-3">
                    <div class="text-center footer-socials">
                        <a href="#"> <i class="fab fa-twitter"></i></a>
                        <a href="#"> <i class="fab fa-instagram"></i></a>
                        <a href="#"> <i class="fab fa-telegram-plane"></i></a>
                        <a href="#"> <i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-1 col-12">
            </div>
            <!-- Right Column (Content) -->
            <div class="col-lg-7 col-12 card shadow p-4">
                <div class="p-3">
                    {!! $master->content !!}
                </div>
            </div>
        </div>
    </div>

    <div class="normal-divider"></div>

    <div class="container-fluid">
        {{-- ======================= Grades Section ======================= --}}
        <section class="Courses-section">
            <div class="mt-107"></div>

            <div class="container">
                <x-section-header title="رتبه های برتر {{$master->fullname}}"  link="#"/>
            </div>

            <div class="mt-107"></div>
            <div class="container-fluid bg-gradient-custom">
                <div class="col-12 upper-box">
                    <x-swiper :id="'gradesCarousel'" :items="$grades" :slidesPerView="5" :type="'card'"
                              :ride="false" :enabledDesc="true" :enabledLink="false" />
                </div>
            </div>

        </section>
        {{-- ======================= End of Grades Section ======================= --}}
    </div>

    <div class="normal-divider"></div>

    <div class="container-fluid">
        {{-- ======================= Courses Section ======================= --}}
        <section class="Courses-section">
            <div class="mt-107"></div>

            <div class="container">
                <x-section-header title="دوره های {{$master->fullname}}"  link="#"/>
            </div>

            <div class="mt-107"></div>
            <div class="container-fluid bg-gradient-custom">
                <div class="col-12 upper-box">
                    <x-swiper :id="'gradesCarousel'" :items="$courses" :slidesPerView="5" :type="'card'"
                              :ride="false" :enabledDesc="false" :enabledLink="true" :viewLink="'frontend.courses'"/>
                </div>
            </div>

        </section>
        {{-- ======================= End of Courses Section ======================= --}}
    </div>
    <div class="normal-divider"></div>

    <div class="container-fluid">
        {{-- ======================= Comments Section ======================= --}}
        <section class="comments-section">
            <div class="mt-107"></div>

            <div class="container">
                <x-section-header title="نظرات دانش آموزان درباره {{$master->fullname}}"  link="#"/>
            </div>

            <div class="mt-107"></div>

            <div class="container">
                <div class="col-12 upper-box">
                    <x-swiper :id="'commentsCarousel'" :items="$comments" :slidesPerView="3" :type="'comments'"
                              :ride="false" :enabledDesc="true" :enabledLink="false"/>
                </div>
            </div>
        </section>
        {{-- ======================= End of Comments Section ======================= --}}
    </div>

@endsection
