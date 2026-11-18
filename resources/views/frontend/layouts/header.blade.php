<header class="sp-nav">
    <a class="sp-brand" href="{{ route('frontend.home') }}">
        <span class="sp-brand__fa">سپاسیران</span>
        <span class="sp-brand__en latin">Sepasiran</span>
    </a>

    <nav class="sp-menu" aria-label="منوی اصلی">
        <ul class="sp-menu__list">
            <li class="sp-menu__item sp-menu__item--link">
                <a class="sp-menu__trigger {{ request()->routeIs('frontend.home') ? 'is-active' : '' }}"
                   href="{{ route('frontend.home') }}">خانه</a>
            </li>
            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger" aria-haspopup="true">سگ‌ها</button>
                <div class="sp-menu__dropdown" role="menu">
                    <a href="{{ route('frontend.dog.supply.index') }}"
                       class="{{ request()->routeIs('frontend.dog.supply.*') ? 'is-active' : '' }}">لیست سگ‌ها</a>
                    <a href="{{ route('frontend.breeders.index') }}">پرورش‌دهندگان</a>
                    <a href="{{ route('frontend.shelter.index') }}">پناهگاه</a>
                </div>
            </li>
            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger" aria-haspopup="true">خدمات</button>
                <div class="sp-menu__dropdown" role="menu">
                    <a href="{{ route('frontend.booking.index') }}"
                       class="{{ request()->routeIs('frontend.booking.*') ? 'is-active' : '' }}">رزرو خدمت</a>
                    <a href="{{ route('frontend.services.index') }}">همه خدمات</a>
                    <a href="{{ route('frontend.coaches.index') }}">مربی‌ها</a>
                    <a href="{{ route('frontend.clinics.index') }}">کلینیک‌ها</a>
                    <a href="{{ route('frontend.clinic.insurance.index') }}">بیمه</a>
                    <a href="{{ route('frontend.online.courses') }}">دوره‌ها</a>
                </div>
            </li>
            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger" aria-haspopup="true">فروشگاه</button>
                <div class="sp-menu__dropdown" role="menu">
                    <a href="{{ route('frontend.stores.index') }}"
                       class="{{ request()->routeIs('frontend.stores.*', 'frontend.products.*') ? 'is-active' : '' }}">محصولات</a>
                </div>
            </li>
            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger" aria-haspopup="true">حساب</button>
                <div class="sp-menu__dropdown" role="menu">
                    @guest
                        <a href="{{ route('login') }}">ورود / ثبت‌نام</a>
                    @else
                        <a href="{{ route('user.dashboard') }}">داشبورد کاربر</a>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="sp-menu__trigger w-100 text-start border-0 bg-transparent">خروج</button>
                        </form>
                    @endguest
                    <a href="{{ route('frontend.about') }}">درباره ما</a>
                    <a href="{{ route('frontend.contact.index') }}">تماس</a>
                </div>
            </li>
        </ul>
    </nav>

    <div class="sp-nav__actions">
        @guest
            <a class="btn btn-ghost" href="{{ route('login') }}">ورود</a>
            <a class="btn btn-primary" href="{{ route('register') }}">ثبت‌نام</a>
        @else
            <a class="btn btn-ghost" href="{{ route('user.dashboard') }}">حساب من</a>
            <a class="btn btn-primary" href="{{ route('frontend.stores.index') }}">فروشگاه</a>
        @endguest
    </div>
</header>
