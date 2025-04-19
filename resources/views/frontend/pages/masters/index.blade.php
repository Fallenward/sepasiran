@extends('frontend.layouts.app')

@section('title', $title)

@section('content')

    <!-- Section 1: Header -->
    <div class="container">
        <div class="col-12">
            {{ Breadcrumbs::render('masters') }}
        </div>
    </div>

    <div class="container">
        <div class="col-12 col-md-6">
            <h2>{{$title}}</h2>
            <x-title-divider></x-title-divider>
        </div>
        <div class="row d-flex justify-content-center">
            <!-- Loop through the masters and display them in a 3x3 grid -->
            @foreach($masters as $index => $master)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-img-container" style="display: flex; justify-content: center; align-items: center; height: 200px; overflow: hidden;">
                            @if($master->image)
                                <img src="{{ asset('storage/' . $master->image) }}" class="card-img-top" alt="{{ $master->fullname }}" style="object-fit: contain; width: 100%; height: 100%;">
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $master->fullname }}</h5>

                            <!-- Shortened description -->
                            <p class="card-text">{{ Str::limit($master->description, 150) }}</p>

                            <div class="d-flex justify-content-between align-items-baseline">
                                <!-- View Button -->
                                <div>
                                    <a href="{{ route('frontend.masters.show', $master->slug) }}" class="btn btn-primary">مشاهده</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- After every 3 masters, create a new row for better structure -->
                @if (($index + 1) % 3 == 0 && $index + 1 != $masters->count())
        </div><div class="row d-flex justify-content-center">
            @endif
            @endforeach
        </div>

        <!-- Pagination links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $masters->links() }} <!-- Display pagination links -->
        </div>
    </div>
@endsection
