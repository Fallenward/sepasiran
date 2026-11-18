<nav class="mobile-nav" aria-label="ناوبری موبایل">
    <a href="{{ route('frontend.home') }}"
       class="{{ request()->routeIs('frontend.home') ? 'is-active' : '' }}">
        <span><i class="fas fa-home"></i></span>خانه
    </a>
    <a href="{{ route('frontend.dog.supply.index') }}"
       class="{{ request()->routeIs('frontend.dog.supply.*') ? 'is-active' : '' }}">
        <span><i class="fas fa-search"></i></span>جستجو
    </a>
    <a href="{{ route('frontend.booking.index') }}"
       class="{{ request()->routeIs('frontend.booking.*') ? 'is-active' : '' }}">
        <span><i class="far fa-calendar-check"></i></span>رزرو
    </a>
    <a href="{{ route('frontend.stores.index') }}"
       class="{{ request()->routeIs('frontend.stores.*', 'frontend.products.*') ? 'is-active' : '' }}">
        <span><i class="fas fa-store"></i></span>فروشگاه
    </a>
    @auth
        <a href="{{ route('user.dashboard') }}"
           class="{{ request()->routeIs('user.*') ? 'is-active' : '' }}">
            <span><i class="far fa-user"></i></span>حساب من
        </a>
    @else
        <a href="{{ route('login') }}">
            <span><i class="far fa-user"></i></span>ورود
        </a>
    @endauth
</nav>
