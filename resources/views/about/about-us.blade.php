@extends('layouts.app')

@section('content')
<style>
    .section-spacing {
        margin-bottom: 5rem;
    }
    .section-title {
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
        margin-bottom: 2rem;
    }
    .section-title::after {
        content: "";
        display: block;
        width: 120px;
        height: 5px;
        background-color: #0d47a1;
        border-radius: 3px;
        margin: 10px auto 0;
    }
    .accordion-button:not(.collapsed) {
        background-color: #e3f2fd;
        color: #000;
    }
    .accordion-button:focus {
        box-shadow: none;
    }
</style>

<div class="bg-white py-5">
    <div class="container-fluid text-end px-5">

        {{-- Section: About Blog --}}
        <div class="row align-items-center text-end section-spacing" style="min-height: 350px;">
            <div class="col-md-6 order-2 order-md-1 d-flex flex-column justify-content-center">
                @if(isset($latestBlog))
                    <h2 class="fw-bold mb-2">{{ $latestBlog->title }}</h2>
                    <div style="height: 4px; width: 64px; background-color: #0d47a1;" class="mb-4"></div>
                    <p class="text-muted">
                        {{ \Illuminate\Support\Str::limit(strip_tags($latestBlog->excerpt ?? $latestBlog->content), 180) }}
                    </p>
                @else
                    <p class="text-danger">محتوایی برای نمایش وجود ندارد.</p>
                @endif
            </div>
            <div class="col-md-6 order-1 order-md-2 text-center">
                @if(isset($latestBlog) && $latestBlog->image)
                    <img src="{{ asset('images/support-card.png') }}" alt="{{ $latestBlog->title }}" class="img-fluid rounded shadow-sm" style="max-width: 500px;">
                @else
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="تصویر پیش‌فرض" class="img-fluid rounded shadow-sm" style="max-width: 500px;">
                @endif
            </div>
        </div>



        {{-- Section: Stats --}}
        <section class="statistics-section section-spacing">
            <div class="container-fluid px-5">
                <div class="row g-4">
                    @foreach($statistics as $item)
                        <div class="col-6 col-md-6 col-lg-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <p class="card-text-counter fs-3 fw-bold text-primary">
                                        {{ $item['counter'] }}<i class="fa fa-plus ms-1"></i>
                                    </p>
                                    <h5 class="card-title-counter">{{ $item['title'] }}</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Section: Goals --}}
        <section class="section-spacing">
            <div class="rounded p-5" style="background-color: #0d47a1; height: 360px;">
                <div class="row text-white flex-row-reverse" style="min-height: 300px;">
                    <div class="col-md-6 d-flex align-items-end justify-content-end pe-4">
                        <img src="{{ asset('images/dog-khati.png') }}" alt="Dog Lineart" class="img-fluid" style="width: 445px; height: 130%;">
                    </div>
                    <div class="col-md-6 text-end d-flex flex-column justify-content-center">
                        <h2 class="fw-bold mb-3">اهداف</h2>
                        <div style="height: 4px; width: 64px; background-color: white;" class="mb-4 ms-auto"></div>
                        <p class="fs-6 lh-lg">
                            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section: FAQ --}}
        <section class="section-spacing text-center">
            <h2 class="fw-bold section-title">سوالات متداول</h2>
            <div class="bg-light rounded overflow-hidden mt-4">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading1">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                جلسات چقدر طول می‌کشد؟
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted text-end">
                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...
                            </div>
                        </div>
                    </div>

                    @foreach ([
                        'آیا امکان آموزش در خانه من هست؟',
                        'مربیان با چه نژادهایی کار می‌کنند؟',
                        'لورم ایپسوم متن ساختگی؟',
                        'لورم ایپسوم متن ساختگی؟'
                    ] as $index => $question)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $index + 2 }}">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index + 2 }}">
                                    {{ $question }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index + 2 }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted text-end">
                                    پاسخ مربوط به سوال: "{{ $question }}"
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
