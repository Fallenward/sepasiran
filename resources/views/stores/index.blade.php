@extends('layouts.app')

@section('title', 'فروشگاه')

@section('content')

    <!-- بخش عنوان و نوار مسیریابی -->
    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #0D47A1;">
        <h1 class="fw-bold mb-2">فروشگاه</h1>
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
            <aside class="col-lg-4 col-md-5 col-12 mb-4">
                <div class="collapse d-md-block" id="filterPanel">
                  <div class="p-3 bg-light rounded w-100" style="direction: rtl; max-width: 350px;">
                    <h5 class="fw-bold">فیلترها</h5>

                    <!-- دسته بندی لوازم -->
                    <div class="mb-4 border rounded p-2">
                      <h6 class="text-white px-2 py-1 rounded" style="background-color: #0d47a1;">دسته بندی لوازم</h6>
                      @foreach([
                        'entertainment' => 'لوازم سرگرمی سگ',
                        'training'      => 'لوازم آموزشی سگ',
                        'lorem1'        => 'لورم',
                        'lorem2'        => 'لورم'
                      ] as $value => $label)
                        <div class="form-check d-flex justify-content-between align-items-center mb-2">
                          <label class="form-check-label me-2 text-nowrap" for="cat_{{ $value }}">{{ $label }}</label>
                          <input class="form-check-input"
                                 type="checkbox"
                                 id="cat_{{ $value }}"
                                 name="category[]"
                                 value="{{ $value }}"
                                 {{ in_array($value, request('category', [])) ? 'checked' : '' }}>
                        </div>
                      @endforeach
                    </div>

                    <!-- اندازه سگ -->
                    <div class="mb-4 border rounded p-2">
                      <h6 class="text-white px-2 py-1 rounded" style="background-color: #0d47a1;">اندازه سگ</h6>
                      @foreach([
                        'mini'   => 'کوچک (Mini)',
                        'medium' => 'متوسط (Medium)',
                        'large'  => 'بزرگ (Large)'
                      ] as $value => $label)
                        <div class="form-check d-flex justify-content-between align-items-center mb-2">
                          <label class="form-check-label me-2" for="size_{{ $value }}">{{ $label }}</label>
                          <input class="form-check-input"
                                 type="checkbox"
                                 id="size_{{ $value }}"
                                 name="size[]"
                                 value="{{ $value }}"
                                 {{ in_array($value, request('size', [])) ? 'checked' : '' }}>
                        </div>
                      @endforeach
                    </div>

                    <form method="GET" action="{{ route('frontend.stores.index') }}">
                      <button type="submit" class="btn btn-primary btn-sm w-100">اعمال فیلترها</button>
                    </form>
                  </div>
                </div>
              </aside>

            <!-- بخش نمایش سگ‌ها سمت چپ -->
            <div class="col-md-8"> <!-- اینجا عرض 2/3 صفحه را اختصاص داده‌ایم -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
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
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
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
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
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
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
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
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
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
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
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
