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
            <div class="col-md-4"> <!-- اینجا عرض 1/3 صفحه را اختصاص داده‌ایم -->
                <div class="p-3 bg-light rounded mb-4">
                    <h5 class="fw-bold">فیلترها</h5>

                    <!-- فیلتر شهر -->
                    <div class="mb-3">
                        <h6>شهر</h6>
                        <div>
                            <input type="checkbox" id="shiraz" value="shiraz">
                            <label for="shiraz" class="ms-2">شیراز</label>
                        </div>
                        <div>
                            <input type="checkbox" id="isfahan" value="isfahan">
                            <label for="isfahan" class="ms-2">اصفهان</label>
                        </div>
                        <div>
                            <input type="checkbox" id="tehran" value="tehran">
                            <label for="tehran" class="ms-2">تهران</label>
                        </div>
                        <div>
                            <input type="checkbox" id="mashhad" value="mashhad">
                            <label for="mashhad" class="ms-2">مشهد</label>
                        </div>
                        <div>
                            <input type="checkbox" id="tabriz" value="tabriz">
                            <label for="tabriz" class="ms-2">تبریز</label>
                        </div>
                    </div>

                </div>

                <!-- فیلتر نژاد -->
                <div class="p-3 bg-light rounded mb-4">
                    <h6>نژاد</h6>
                    <div>
                        <input type="checkbox" id="germanshepherd" value="germanshepherd">
                        <label for="germanshepherd" class="ms-2">ژرمن شپرد</label>
                    </div>
                    <div>
                        <input type="checkbox" id="husky" value="husky">
                        <label for="husky" class="ms-2">هاسکی</label>
                    </div>
                    <div>
                        <input type="checkbox" id="doberman" value="doberman">
                        <label for="doberman" class="ms-2">دوبرمن</label>
                    </div>
                    <div>
                        <input type="checkbox" id="spitz" value="spitz">
                        <label for="spitz" class="ms-2">اشپیتز</label>
                    </div>
                    <div>
                        <input type="checkbox" id="rottweiler" value="rottweiler">
                        <label for="rottweiler" class="ms-2">روتوایلر</label>
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
                    <!-- سگ 1 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">ژرمن شپرد</h6>
                                <small>(از 10 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
                            </div>
                            <div class="card-footer text-muted">
                                مستر روتوایلر - پرورش دهنده
                            </div>
                        </div>
                    </div>

                    <!-- سگ 2 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">هاسکی</h6>
                                <small>(از 8 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
                            </div>
                            <div class="card-footer text-muted">
                                بنیامین رضایی - پرورش دهنده
                            </div>
                        </div>
                    </div>

                    <!-- سگ 3 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">دوبرمن</h6>
                                <small>(از 12 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
                            </div>
                            <div class="card-footer text-muted">
                                دامپزشکی دامون - پرورش دهنده
                            </div>
                        </div>
                    </div>

                    <!-- سگ 4 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">اشپیتز</h6>
                                <small>(از 7 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
                            </div>
                            <div class="card-footer text-muted">
                                حسین فلاح - پرورش دهنده
                            </div>
                        </div>
                    </div>

                    <!-- سگ 5 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">روتوایلر</h6>
                                <small>(از 15 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
                            </div>
                            <div class="card-footer text-muted">
                                آریا توکلی - پرورش دهنده
                            </div>
                        </div>
                    </div>

                    <!-- سگ 6 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">ژرمن شپرد</h6>
                                <small>(از 9 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
                            </div>
                            <div class="card-footer text-muted">
                                مستر روتوایلر - پرورش دهنده
                            </div>
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
