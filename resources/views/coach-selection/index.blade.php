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
                    <a href="{{ route('frontend.coach-selection.index') }}" class="text-white text-decoration-none">انتخاب مربی</a>
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
                    <h6>تخصص ها</h6>
                    <div>
                        <input type="checkbox" id="mini" value="mini">
                        <label for="mini" class="ms-2">لورم اپسیروم</label>
                    </div>
                    <div>
                        <input type="checkbox" id="medium" value="medium">
                        <label for="mini" class="ms-2">لورم اپسیروم</label>
                    </div>
                    <div>
                        <input type="checkbox" id="large" value="large">
                        <label for="mini" class="ms-2">لورم اپسیروم</label>
                    </div>
                </div>
            </div>

            <!-- بخش نمایش سگ‌ها سمت چپ -->
            <div class="col-md-8"> <!-- اینجا عرض 2/3 صفحه را اختصاص داده‌ایم -->
                <div class="row g-4">
                    <!-- سگ 1 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">بنیامین رضایی</h6>
                                <small>(از 10 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">بنیامین رضایی</h6>
                                <small>(از 10 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">بنیامین رضایی</h6>
                                <small>(از 10 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">بنیامین رضایی</h6>
                                <small>(از 10 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">بنیامین رضایی</h6>
                                <small>(از 10 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ asset('storage/Annotation 2025-04-19 105015.jpg') }}" class="card-img-top" alt="سگ">
                            <div class="card-body">
                                <h6 class="fw-bold">بنیامین رضایی</h6>
                                <small>(از 10 نظر)</small>
                                <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                                <form action="{{ route('frontend.dog.order') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                </form>
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
