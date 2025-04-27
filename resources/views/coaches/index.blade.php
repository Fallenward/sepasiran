@extends('layouts.app')

@section('title', 'انتخاب مربی')

@section('content')

    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #5a8dee;">
        <h1 class="fw-bold mb-2">انتخاب مربی</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.coaches.index') }}" class="text-white text-decoration-none">انتخاب مربی</a>
                </li>
            </ol>
        </nav>
    </div>

    <div class="container">
        <div class="row">
            <!-- بخش فیلتر سمت راست -->
            <div class="col-md-4">
                <div class="p-3 bg-light rounded mb-4">
                    <h5 class="fw-bold">فیلترها</h5>

                    <!-- فیلتر شهر -->
                    <div class="mb-3">
                        <h6>شهر</h6>
                        @foreach($cities as $city)
                            <div>
                                <input type="checkbox" name="city[]" value="{{ $city->id }}" id="city-{{ $city->id }}">
                                <label for="city-{{ $city->id }}" class="ms-2">{{ $city->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- فیلتر نژاد -->
                    <div class="mb-3">
                        <h6>نژاد</h6>
                        @foreach($breeds as $breed)
                            <div>
                                <input type="checkbox" name="breed[]" value="{{ $breed->id }}" id="breed-{{ $breed->id }}">
                                <label for="breed-{{ $breed->id }}" class="ms-2">{{ $breed->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- فیلتر تخصص‌ها -->
                    <div class="mb-3">
                        <h6>تخصص‌ها</h6>
                        @foreach($specialties as $specialty)
                            <div>
                                <input type="checkbox" name="specialty[]" value="{{ $specialty->id }}" id="specialty-{{ $specialty->id }}">
                                <label for="specialty-{{ $specialty->id }}" class="ms-2">{{ $specialty->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- بخش نمایش مربیان -->
            <div class="col-md-8">
                <div class="row g-4">
                    @foreach($coaches as $coach)
                        <div class="col-md-4">
                            <div class="card">
                                <img src="{{ asset('storage/' . $coach->image) }}" class="card-img-top" alt="مربی">
                                <div class="card-body">
                                    <h6 class="fw-bold">{{ $coach->name }}</h6>
                                    <p class="text-muted small">{{ $coach->description }}</p>
                                    <form action="{{ route('frontend.dog.order') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


@endsection
