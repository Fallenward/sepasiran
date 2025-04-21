@extends('layouts.app')

@section('title', 'فروشگاه')

@section('content')

    <!-- بخش عنوان و نوار مسیریابی -->
    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #5a8dee;">
        <h1 class="fw-bold mb-2">پناهگاه</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.stores.index') }}" class="text-white text-decoration-none">فروشگاه</a>
                </li>
            </ol>
        </nav>
    </div>

    <div class="container">
        <div class="row">
            <!-- بخش فیلتر سمت راست -->
            <div class="col-md-4"> <!-- اینجا عرض 1/3 صفحه را اختصاص داده‌ایم -->
                <div class="p-3 bg-light rounded mb-4">
                    <h5 class="fw-bold">فیلترها</h5>

                    <!-- فیلتر شهر -->
                    <div class="mb-3">
                        <h6>دسته بندی لوازم</h6>
                        <div>
                            <input type="checkbox" id="shiraz" value="shiraz">
                            <label for="shiraz" class="ms-2">لوازم سرگرمی سگ</label>
                        </div>
                        <div>
                            <input type="checkbox" id="isfahan" value="isfahan">
                            <label for="isfahan" class="ms-2">لوازم اموزشی سگ</label>
                        </div>
                        <div>
                            <input type="checkbox" id="tehran" value="tehran">
                            <label for="tehran" class="ms-2">لورم</label>
                        </div>
                        <div>
                            <input type="checkbox" id="tehran" value="tehran">
                            <label for="tehran" class="ms-2">لورم</label>
                        </div>
                    </div>

                </div>


                <!-- فیلتر اندازه سگ -->
                <div class="p-3 bg-light rounded">
                    <h6>اندازه سگ</h6>
                    <div>
                        <input type="checkbox" id="mini" value="mini">
                        <label for="mini" class="ms-2">کوچک (Mini)</label>
                    </div>
                    <div>
                        <input type="checkbox" id="medium" value="medium">
                        <label for="medium" class="ms-2">متوسط (Medium)</label>
                    </div>
                    <div>
                        <input type="checkbox" id="large" value="large">
                        <label for="large" class="ms-2">بزرگ (Large)</label>
                    </div>
                </div>
            </div>

            <!-- بخش نمایش سگ‌ها سمت چپ -->
            <div class="col-md-8"> <!-- اینجا عرض 2/3 صفحه را اختصاص داده‌ایم -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">پوزبند وایبر</h6>
                                <p class="text-muted small">لوازم سگ</p>
                                <form action="#" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">خرید</button>
                                </form>
                                <button type="submit" class="btn btn-outline-primary btn-sm">1,000,000 تومان</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">پوزبند وایبر</h6>
                                <p class="text-muted small">لوازم سگ</p>
                                <form action="#" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">خرید</button>
                                </form>
                                <button type="submit" class="btn btn-outline-primary btn-sm">1,000,000 تومان</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">پوزبند وایبر</h6>
                                <p class="text-muted small">لوازم سگ</p>
                                <form action="#" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">خرید</button>
                                </form>
                                <button type="submit" class="btn btn-outline-primary btn-sm">1,000,000 تومان</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">پوزبند وایبر</h6>
                                <p class="text-muted small">لوازم سگ</p>
                                <form action="#" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">خرید</button>
                                </form>
                                <button type="submit" class="btn btn-outline-primary btn-sm">1,000,000 تومان</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">پوزبند وایبر</h6>
                                <p class="text-muted small">لوازم سگ</p>
                                <form action="#" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">خرید</button>
                                </form>
                                <button type="submit" class="btn btn-outline-primary btn-sm">1,000,000 تومان</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">پوزبند وایبر</h6>
                                <p class="text-muted small">لوازم سگ</p>
                                <form action="#" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">خرید</button>
                                </form>
                                <button type="submit" class="btn btn-outline-primary btn-sm">1,000,000 تومان</button>
                            </div>
                        </div>
                    </div>

                <!-- دکمه مشاهده بیشتر -->
                <div class="text-center mt-4">
                    <a href="#" class="btn btn-secondary">مشاهده بیشتر</a>
                </div>
            </div>
        </div>
    </div>


@endsection
