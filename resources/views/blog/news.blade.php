@extends('layouts.app')

@section('content')

{{-- Featured News --}}
<div class="container my-5">
    <div class="row align-items-center text-end" style="min-height: 350px;">
        <div class="col-md-6 order-2 order-md-1 d-flex flex-column justify-content-center">
            <h2 class="fw-bold mb-2">اخبار</h2>
            <div style="height: 6px; width: 72px; background-color: #0d47a1;" class="mb-4 rounded"></div>

            @if(isset($latestNews))
                <h4 class="fw-bold mb-2">{{ $latestNews->title }}</h4>
                <p class="text-muted">
                    {{ \Illuminate\Support\Str::limit(strip_tags($latestNews->excerpt ?? $latestNews->content), 180) }}
                </p>
            @else
                <p class="text-danger">محتوایی برای نمایش وجود ندارد.</p>
            @endif
        </div>

        <div class="col-md-6 order-1 order-md-2 text-center">
            @if(isset($latestNews) && $latestNews->image)
                <img src="{{ asset('storage/' . $latestNews->image) }}" alt="{{ $latestNews->title }}" class="img-fluid rounded shadow-sm" style="max-width: 300px;">
            @else
                <img src="{{ asset('images/placeholder.jpg') }}" alt="تصویر پیش‌فرض" class="img-fluid rounded shadow-sm" style="max-width: 300px;">
            @endif
        </div>
    </div>
</div>

{{-- Divider Title --}}
<div class="container my-5">
    <div class="text-end">
        <h3 class="fw-bold">سایر اخبار</h3>
        <div style="height: 4px; width: 64px; background-color: #0d47a1;" class="rounded mt-2 mb-4"></div>
    </div>
</div>

{{-- News Grid --}}
<div class="container pb-5">
    <div class="text-end row row-cols-1 row-cols-md-2 g-4">
        @foreach($newsItems->take(8) as $news)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    @if($news->image)
                        <img src="{{ asset('storage/' . $news->image) }}" class="card-img-top" alt="{{ $news->title }}">
                    @else
                        <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top" alt="پیش‌فرض">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $news->title }}</h5>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags($news->excerpt ?? $news->content), 100) }}</p>
                        <a href="{{ route('frontend.breeders.show', $news->id) }}" class="btn btn-primary btn-sm">مطالعه بیشتر</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($newsItems->count() > 8)
        {{-- See More Button --}}
        <div class="text-center mt-4">
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#moreNews" aria-expanded="false" aria-controls="moreNews">
                مشاهده بیشتر
            </button>
        </div>

        {{-- Collapsible Extra News --}}
        <div class="collapse mt-4" id="moreNews">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 text-end">
                @foreach($newsItems->slice(8) as $news)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            @if($news->image)
                                <img src="{{ asset('storage/' . $news->image) }}" class="card-img-top" alt="{{ $news->title }}">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top" alt="پیش‌فرض">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $news->title }}</h5>
                                <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags($news->excerpt ?? $news->content), 100) }}</p>
                                <a href="{{ route('frontend.blogs.show', $news->id) }}" class="btn btn-primary btn-sm">مطالعه بیشتر</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($newsItems->isEmpty())
        <div class="text-center text-danger mt-4">
            هیچ خبری برای نمایش وجود ندارد.
        </div>
    @endif
</div>


@endsection
