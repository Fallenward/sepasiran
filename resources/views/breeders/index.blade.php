@extends('layouts.app')

@section('title', 'پرورش دهنده')

@section('content')

    <!-- بخش بنر بالای صفحه -->
    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #5a8dee;">
        <h1 class="fw-bold mb-2">پرورش دهنده</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.breeders.index') }}" class="text-white text-decoration-none">پرورش دهنده</a>
                </li>
            </ol>
        </nav>
    </div>

    <!-- بخش اصلی صفحه -->
    <div class="container">
        <div class="row">
            <!-- بخش فیلتر سمت راست -->
            <div class="col-md-4">
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

            <!-- بخش نمایش کلینیک‌ها -->
            <div class="col-md-8">
                <div class="row g-4">
                    @foreach(range(1, 6) as $index)
                        <div class="col-md-4">
                            <div class="card">
                                <img src="{{ asset('images/dogPicture.jpg') }}" class="card-img-top" alt="سگ">
                                <div class="card-body">
                                    <h6 class="fw-bold">مستر روتوایلر</h6>
                                    <small>(از 10 نظر)</small>
                                    <p class="text-muted small">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>

                                    <form action="#" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm">مشاهده</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- دکمه مشاهده بیشتر -->
                <div class="text-center mt-4">
                    <a href="#" class="btn btn-secondary">مشاهده بیشتر</a>
                </div>
            </div>

        </div> <!-- پایان row -->

    </div> <!-- پایان container -->

@endsection

<!-- استایل‌ها -->
<style>
    .service-box {
        background-color: #f8f9fa; /* رنگ پس‌زمینه */
        border-radius: 15px; /* گوشه‌های گرد */
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* سایه برای برجسته‌تر کردن مستطیل */
    }

    .service-box .content {
        padding-right: 20px;
    }

    .service-box .content h5 {
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .service-box .content p {
        font-size: 1rem;
        line-height: 1.6;
        color: #6c757d;
    }

    .service-box .services-list {
        list-style-type: none;
        padding-left: 0;
        margin-top: 20px;
    }

    .service-box .services-list li {
        margin-bottom: 10px;
    }

    .service-box .services-list a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
    }

    .service-box .services-list a:hover {
        text-decoration: underline;
    }

    .service-box .image img {
        max-width: 100%;
        border-radius: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .comments-section {
        background-color: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .comments-section h5 {
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .comment {
        margin-bottom: 20px;
    }

    .comment h6 {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .comment p {
        font-size: 1rem;
        color: #6c757d;
        line-height: 1.6;
    }

    .comments-section .btn {
        margin-top: 20px;
    }
</style>
