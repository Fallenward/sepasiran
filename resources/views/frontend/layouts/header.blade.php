<header>
    <div class="container p-0 iran-icon">
        <img src="{{ asset('images/iran.png') }}" alt="بنر پرچم ایران" class="img-fluid">
    </div>
    <nav class="navbar navbar-expand-lg navbar-light">

        <div class="container p-0">

            <div class="d-flex">
                <!-- Menu Icon (Toggler) on the right -->
                <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#mobileMenu" aria-controls="mobileMenu"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Logo and Brand -->
                <div class="d-flex align-items-center mx-3">
                    <a class="navbar-brand" href="{{ route('frontend.home') }}">
                        <img src="{{ asset('images/fav.png') }}" alt="لوگو سپاس ایران" height="50">
                    </a>
                </div>
            </div>

            <!-- Auth Buttons for Small Devices -->
            <div class="d-lg-none">
                @if(is_null(auth()->user()))
                    <a href="{{ route('login') }}" class="btn btn-primary me-2">ورود / ثبت نام</a>
                @else
                    <a href="{{ route('coach.dashboard') }}" class="btn btn-primary">داشبورد</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline d-none d-md-block me-2">
                        @csrf
                        <button type="submit" class="btn btn-danger">خروج</button>
                    </form>
                @endif
            </div>

            <!-- Collapsible Content for Desktop -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="#">صفحه اصلی</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">میز خدمت</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">معرفی سازمان</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">اخبار</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">پشتیبانی</a></li>
                </ul>

                <!-- Search Form -->
                <form class="d-flex me-auto">
                    <div class="input-group search-area">
                        <button class="input-group-text bg-white search-btn"><i class="fas fa-search"></i></button>
                        <input type="text" class="form-control" placeholder="جستجو ...">
                    </div>
                </form>

                <!-- Auth Buttons for Desktop -->
                <div class="d-none d-lg-block">
                    @if(is_null(auth()->user()))
                        <a href="{{ route('login') }}" class="btn btn-primary me-2">ورود / ثبت نام</a>
                    @else
                        <a href="{{ route('coach.dashboard') }}" class="btn btn-primary me-2">داشبورد</a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">خروج</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Sidebar Menu -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">منو</h5>
            <button type="button" class="btn-close text-reset me-1" data-bs-dismiss="offcanvas" aria-label="بستن"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#">خانه</a></li>
                <li class="nav-item"><a class="nav-link" href="#">دوره ها</a></li>
                <li class="nav-item"><a class="nav-link" href="#">اساتید</a></li>
                <li class="nav-item"><a class="nav-link" href="#">رتبه های برتر</a></li>
                <li class="nav-item"><a class="nav-link" href="#">اخبار</a></li>
                <li class="nav-item"><a class="nav-link" href="#">مقالات</a></li>
            </ul>

            <!-- Auth Buttons in Mobile Sidebar -->
            <div class="mt-4">
                @if(is_null(auth()->user()))
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">ورود / ثبت نام</a>
                @else
                    <a href="{{ route('breeder.dashboard') }}" class="btn btn-primary w-100 mb-2">داشبورد</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">خروج</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</header>
