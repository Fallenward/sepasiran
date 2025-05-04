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
            <x-section-header title="اخبار" buttonText="مشاهده همه" link="#"/>
        </div>

        <div class="mt-107"></div>

        <div class="container">
            <div class="col-12 upper-box row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <img src="{{asset($blog->image)}}" class="card-img-top img-fluid" alt="{{$blog->title}}">
                        <div class="card-body">
                            <p class="card-text">{{$blog->content}}</p>
                        </div>
                    </div>
                </div>
                <style>
                    .card{
                        border-bottom: 4px solid #15499e;
                    }
                    img{
                        object-fit: cover;
                    }
                </style>
                <div class="col-12 col-md-6">
                    <div class="col-12 col-md-6" style="display:contents;">
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-4 d-flex">
                                    <img src="{{asset($blog->image)}}" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">{{$blog->title}}</h5>
                                        <p class="card-text">{{$blog->content}}</p>
                                        <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-md-6" style="display:contents;">
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-4 d-flex">
                                    <img src="{{asset($blog->image)}}" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">{{$blog->title}}</h5>
                                        <p class="card-text">{{$blog->content}}</p>
                                        <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    <section class="coverage-section">
        <div class="mt-107"></div>
        <div class="container-fluid p-5">
            <div class="row align-items-center">
                <!-- col-7 with title, content, and button -->
                <div class="col-md-7">
                    <h2 class="mb-3 text-white">پناهگاه</h2>
                    <x-title-divider></x-title-divider>
                    <p class="my-4 text-white">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده
                        از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای
                        شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای
                        زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم
                        افزارها شناخت شود.</p>
                    <a href="{{ route('frontend.shelter.index') }}" class="btn btn-light mb-4">مشاهده پناهگاه</a>

                </div>

                <!-- col-5 with image -->
                <div class="col-md-5">
                    <img src="{{ asset('images/shelther.png') }}" alt="Placeholder image" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>
    {{-- ======================= End of Coverage Section ======================= --}}


    {{-- ======================= Best Section ======================= --}}
    <section class="best-section">

        <div class="mt-107"></div>
        <div class="container">
            <x-section-header title="برترین ها" buttonText="مشاهده همه" link="#"/>
        </div>

        <div class="mt-107"></div>

        <div class="container">
            <div class="col-12 upper-box">
                <x-swiper :id="'blogsCarousel'" :items="$blogs" :slidesPerView="3" :type="'bests'"
                          :ride="false" :enabledDesc="false" :enabledLink="true" :viewLink="'frontend.blogs.show'"/>
            </div>
        </div>
    </section>
    {{-- ======================= End of Best Section ======================= --}}

    <div class="mt-5 text-center">
        <h5 class="fw-bold mb-4">پیوند به سایت‌های دیگر</h5>
        <div class="d-flex justify-content-center gap-4">
            <a href="#" class="card d-flex align-items-center custom-link-card p-4 text-decoration-none">
                <img src="{{ asset('images/iran.png') }}" alt="دولت" class="rounded-circle" width="50"
                     height="50">
                <span class="ms-3 custom-text">پایگاه اطلاع رسانی دولت</span>
            </a>
            <a href="#" class="card d-flex align-items-center custom-link-card p-4 text-decoration-none">
                <img src="{{ asset('images/iran.png') }}" alt="ریاست جمهوری" class="rounded-circle" width="50"
                     height="50">
                <span class="ms-3 custom-text">پایگاه اطلاع رسانی ریاست جمهوری</span>
            </a>
            <a href="#" class="card d-flex align-items-center custom-link-card p-4 text-decoration-none">
                <img src="{{ asset('images/iran.png') }}" alt="مقام معظم رهبری" class="rounded-circle" width="50"
                     height="50">
                <span class="ms-3 custom-text">پایگاه اطلاع رسانی دفتر مقام معظم رهبری</span>
            </a>
        </div>
    </div>

@endsection
