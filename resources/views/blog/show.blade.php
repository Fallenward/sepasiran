@extends('layouts.app')

@section('title', 'آخرین مطالب')

@section('content')
    <div class="container mt-5">
        <!-- بخش آخرین مطالب -->
        <h2 class="fw-bold mb-4">آخرین مطالب</h2>

        <!-- لیست مقالات وبلاگ -->
        <div class="row">
            @foreach(range(1, 5) as $index) <!-- فرضا 5 پست را نمایش می‌دهیم -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">نگهداری و مراقبت از سگ ژرمن شپرد</h5>
                        <p class="card-text">
                            <small class="text-muted">10:00 - 24 آذر 02</small>
                        </p>
                        <p class="card-text">
                            سگ‌های ژرمن شپرد باهوش، وفادار و پرانرژی هستند. در این مقاله به نکات مهم نگهداری و مراقبت از این سگ‌ها پرداخته‌ایم...
                        </p>
                        <a href="#" class="btn btn-primary">ادامه مطلب</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- فیلترها -->
        <div class="row mt-5">
            <div class="col-md-3">
                <div class="p-3 bg-light rounded">
                    <h6>فیلترها</h6>
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

                    <!-- فیلتر نژاد -->
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

                    <!-- فیلتر اندازه سگ -->
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

            <!-- فیلترهای محبوب -->
            <div class="col-md-9">
                <div class="p-3 bg-light rounded">
                    <h6>محبوب ترین ها</h6>
                    <div class="mb-3">
                        <h6>چرا دوبرمن بهترین سگ نگهبان است؟</h6>
                        <p>لورم ایپسوم متن ساختگی با تولید سادگی...</p>
                        <small class="text-muted">10:00 - 24 آذر 02</small>
                    </div>
                    <div class="mb-3">
                        <h6>سگ‌های ژرمن شپرد باهوش و وفادار</h6>
                        <p>لورم ایپسوم متن ساختگی...</p>
                        <small class="text-muted">10:00 - 24 آذر 02</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
