@extends('layouts.app')

@section('title', $title)

@php
    $heroImage = null;
    if (isset($sliders) && $sliders->isNotEmpty() && !empty($sliders->first()->image)) {
        $heroImage = asset('storage/' . ltrim($sliders->first()->image, '/'));
    }
@endphp

@section('content')
    @php
        $heroProps = [
            'brand' => 'سپاسیران',
            'title' => 'همه‌چیز برای همراه چهارپای شما',
            'lede' => 'بازار سگ، مربی، کلینیک و فروشگاه — مینیمال، مطمئن، فارسی.',
            'dogsUrl' => route('frontend.dog.supply.index'),
            'servicesUrl' => route('frontend.services.index'),
            'heroImage' => $heroImage,
        ];
    @endphp
    <div data-vue="home-hero" data-props='@json($heroProps)'></div>

    <section class="section">
        <div class="wrap">
            <div class="section__head">
                <h2>از کجا شروع می‌کنید؟</h2>
                <p>یک مسیر را انتخاب کنید — بدون شلوغی کارت و بنر.</p>
            </div>
            <div class="paths">
                <a class="path" href="{{ route('frontend.dog.supply.index') }}">
                    <div>
                        <h3>سگ‌ها</h3>
                        <p>لیست نژاد، شهر و مشخصات</p>
                    </div>
                    <div class="path__mark" aria-hidden="true">◆</div>
                </a>
                <a class="path" href="{{ route('frontend.services.index') }}">
                    <div>
                        <h3>خدمات</h3>
                        <p>مربی، کلینیک، بیمه</p>
                    </div>
                    <div class="path__mark" aria-hidden="true">✦</div>
                </a>
                <a class="path" href="{{ route('frontend.stores.index') }}">
                    <div>
                        <h3>فروشگاه</h3>
                        <p>غذا، لوازم، مراقبت</p>
                    </div>
                    <div class="path__mark" aria-hidden="true">◇</div>
                </a>
            </div>
        </div>
    </section>

    @if(isset($services) && $services->isNotEmpty())
        <section class="section" style="padding-top:0">
            <div class="wrap">
                <div class="section__head">
                    <h2>خدمات</h2>
                    <p>مربی، کلینیک، بیمه و دوره — انتخاب سریع.</p>
                </div>
                <div class="chip-row">
                    @foreach($services->take(8) as $service)
                        <a class="chip" href="{{ $service->route_link ?? route('frontend.services.show', $service->slug) }}">
                            {{ $service->title ?? $service->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section" style="padding-top:0">
        <div class="wrap">
            <div class="section__head">
                <h2>پناهگاه و اعتماد</h2>
                <p>فضایی برای آشنایی با پناهگاه و همراهی مسئولانه.</p>
            </div>
            <a class="path" href="{{ route('frontend.shelter.index') }}">
                <div>
                    <h3>پناهگاه</h3>
                    <p>مشاهده حیوانات نیازمند خانه و حمایت</p>
                </div>
                <div class="path__mark" aria-hidden="true">♡</div>
            </a>
        </div>
    </section>

    @if(isset($blogs) && $blogs->isNotEmpty())
        <section class="section" style="padding-top:0">
            <div class="wrap">
                <div class="section__head">
                    <h2>نکته‌ها و اخبار</h2>
                    <p>یک بخش، یک کار — بدون شلوغی.</p>
                </div>
                <div class="grid grid-3">
                    @foreach($blogs as $blog)
                        <article class="tile">
                            <a class="tile-link" href="{{ route('frontend.blogs.show', $blog->slug) }}">
                                <div class="tile__media tile__media--square">
                                    @if($blog->image)
                                        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}">
                                    @endif
                                </div>
                                <div class="tile__body">
                                    <h3>{{ Str::limit($blog->title, 48) }}</h3>
                                    <div class="tile__meta">
                                        <span>{{ Str::limit(strip_tags($blog->description ?? $blog->content ?? ''), 60) }}</span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
