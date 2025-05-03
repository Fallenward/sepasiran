@extends('layouts.app')

@section('title', 'تهیه سگ')

@section('content')

    <!-- بخش عنوان و نوار مسیریابی -->
    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #5a8dee;">
        <h1 class="fw-bold mb-2">تهیه سگ</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.dog.supply.index') }}" class="text-white text-decoration-none">تهیه سگ</a>
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
                                <input type="checkbox" id="city_{{ $city->id }}" name="city[]" value="{{ $city->id }}" {{ in_array($city->id, request('city', [])) ? 'checked' : '' }}>
                                <label for="city_{{ $city->id }}" class="ms-2">{{ $city->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- فیلتر نژاد -->
                    <div class="mb-3">
                        <h6>نژاد</h6>
                        @foreach($breeds as $breed)
                            <div>
                                <input type="checkbox" id="breed_{{ $breed->id }}" name="breed[]" value="{{ $breed->id }}" {{ in_array($breed->id, request('breed', [])) ? 'checked' : '' }}>
                                <label for="breed_{{ $breed->id }}" class="ms-2">{{ $breed->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- فیلتر اندازه سگ -->
                    <div class="mb-3">
                        <h6>اندازه سگ</h6>
                        <div>
                            <input type="checkbox" id="mini" name="size[]" value="mini" {{ in_array('mini', request('size', [])) ? 'checked' : '' }}>
                            <label for="mini" class="ms-2">کوچک (Mini)</label>
                        </div>
                        <div>
                            <input type="checkbox" id="medium" name="size[]" value="medium" {{ in_array('medium', request('size', [])) ? 'checked' : '' }}>
                            <label for="medium" class="ms-2">متوسط (Medium)</label>
                        </div>
                        <div>
                            <input type="checkbox" id="large" name="size[]" value="large" {{ in_array('large', request('size', [])) ? 'checked' : '' }}>
                            <label for="large" class="ms-2">بزرگ (Large)</label>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('frontend.dog.supply.index') }}">
                        <button type="submit" class="btn btn-primary btn-sm w-100">اعمال فیلترها</button>
                    </form>
                </div>
            </div>

            <!-- بخش نمایش سگ‌ها -->
            <div class="col-md-8">
                <div class="row g-4">
                    @foreach($dogs as $dog)
                        <div class="col-md-4">
                            <div class="card">
                                <img src="{{ asset('storage/' . $dog->image) }}" class="card-img-top" alt="{{ $dog->name }}">
                                <div class="card-body">
                                    <h6 class="fw-bold">{{ $dog->breed->name }}</h6>
                                    <small>({{ $dog->reviews_count }} نظر)</small>
                                    <p class="text-muted small">{{ $dog->description }}</p>
                                    <form action="{{ route('frontend.dog.order') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                    </form>
                                </div>
                                <div class="card-footer text-muted">
                                    {{ $dog->owner->name }} - پرورش دهنده
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- دکمه مشاهده بیشتر -->
                <div class="text-center mt-4">
                    {{ $dogs->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
