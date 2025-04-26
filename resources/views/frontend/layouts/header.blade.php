<header>
    {{-- بنر بالایی با پرچم ایران --}}
    <div class="w-100">
        <img src="{{ asset('images/IranBanner.jpg') }}" alt="بنر پرچم ایران" class="img-fluid w-100">
    </div>

    {{-- ناوبری اصلی --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light py-3 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">

            {{-- لوگو سمت راست --}}
            <a class="navbar-brand ms-3" href="{{ route('frontend.home') }}">
                <img src="{{ asset('images/fav.png') }}" alt="لوگو سپاس ایران" height="50">
            </a>

            {{-- منوی ناوبری --}}
            <ul class="navbar-nav flex-row flex-wrap justify-content-center fw-bold" style="gap: 2rem; font-size: 1.1rem;">


                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.home') }}">صفحه اصلی</a></li>
                <li class="nav-item"><a class="nav-link" href="#">میز خدمت</a></li>
                <li class="nav-item"><a class="nav-link" href="#">معرفی سازمان</a></li>
                <li class="nav-item"><a class="nav-link" href="#">اخبار</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.support.index') }}">پشتیبانی</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.blog.index') }}">وبلاگ</a></li>

                <li class="nav-item">
                    <form class="d-flex align-items-center">
                        <input class="form-control me-2" type="search" placeholder="جست‌وجو" aria-label="Search" style="min-width: 180px;">
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </li>

                <li class="nav-item"><a class="nav-link" href="#">ورود / ثبت نام</a></li>
            </ul>
        </div>
    </nav>

</header>
