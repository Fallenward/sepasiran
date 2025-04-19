@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp

@extends('frontend.layouts.app')

@section('title', $title)

@section('content')

    <!-- Section 1: Header -->
    <div class="container">
        <div class="col-12">
            {{ Breadcrumbs::render('blogs.show.category',$category) }}
        </div>
    </div>



    <div class="container">
        <div class="col-12 col-md-6">
            <h2>{{$title}}</h2>
            <x-title-divider></x-title-divider>

        </div>
        <div class="row d-flex justify-content-center">
            <!-- Loop through the blogs and display them in a 3x3 grid -->
            @foreach($blogs as $index => $blog)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        @if($blog->image)
                            <img src="{{ url($blog->image) }}" class="card-img-top" alt="{{ $blog->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $blog->title }}</h5>

                            <!-- Shortened content -->
                            <p class="card-text">{{ Str::limit($blog->content, 150) }}</p>

                            <div class="d-flex justify-content-between align-items-baseline">
                                <!-- Persian Date Format -->
                                @php
                                    $carbonDate = Carbon::parse($blog->created_at);
                                    $persianDate = Jalalian::fromCarbon($carbonDate)->format('j F Y');
                                @endphp
                                <div>
                                    <span>{{ $persianDate }} <i class="fa fa-calendar-alt"></i> </span>
                                </div>

                                <!-- View Button -->
                                <div>
                                    <a href="{{ route('frontend.blogs.show', $blog->slug) }}" class="btn btn-primary">مشاهده</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- After every 3 blogs, create a new row for better structure -->
                @if (($index + 1) % 3 == 0 && $index + 1 != $blogs->count())
        </div><div class="row d-flex justify-content-center">
            @endif
            @endforeach
        </div>

        <!-- Pagination links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $blogs->links() }} <!-- Display pagination links -->
        </div>
    </div>
@endsection
