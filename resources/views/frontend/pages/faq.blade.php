@extends('frontend.layouts.app')

@section('title', $title)

@section('content')
    <!-- Section 1: Header -->
    <div class="container">
        <div class="col-12">
            {{ Breadcrumbs::render('faq') }}
        </div>
    </div>

    <div class="container">
        <div class="row d-flex align-items-center">
            <!-- Logo Column -->
            <div class="col-12 col-md-2 text-center text-md-start mb-3 mb-md-0 faq-logo">
                <img class="img-fluid" src="{{asset($settings->footer_setting_logo)}}" alt="Logo">
            </div>

            <!-- Vertical Divider (Hidden on Small Screens) -->
            <div class="col-auto d-none d-md-block">
                <x-line-divider-vertical></x-line-divider-vertical>
            </div>
            <div class="col-auto d-block d-md-none">
                <x-line-divider></x-line-divider>
            </div>
            <!-- Text Column -->
            <div class="col-12 col-md-8 faq-text">
                <p>
                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها
                    و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و
                    کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد کتابهای زیادی در شصت و سه درصد گذشته حال و
                    آینده شناخت فراوان جامعه و متخصصان را می طلبد تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه
                    ای علی الخصوص طراحان خلاقی
                </p>
            </div>
        </div>
    </div>
    <div class="normal-divider"></div>

    <div class="container">
        <div class="col-12 d-flex">
            <div>
                <h2>{{ $title }}</h2>
            </div>
        </div>
        <div class="col-12">
            <div>
                <div class="accordion" id="accordionFaq">
                    @foreach ($items as $index => $item)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="{{ $item['heading_id'] }}">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#{{ $item['id'] }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="{{ $item['id'] }}">
                    <span class="icon-container">
                        <i class="fa fa-question"></i>
                    </span>
                                    <span>{{ $item['title'] }}</span>
                                </button>
                            </h2>
                            <div id="{{ $item['id'] }}"
                                 class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                 aria-labelledby="{{ $item['heading_id'] }}"
                                 data-bs-parent="#accordionFaq">
                                <div class="accordion-body">
                                    <p>{{ $item['content'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="normal-divider"></div>

@endsection
