<nav class="mobile-nav" aria-label="ناوبری موبایل">
    <a href="{{ route('frontend.home') }}"
       class="{{ request()->routeIs('frontend.home') ? 'is-active' : '' }}">
        <span><i class="fas fa-home"></i></span>خانه
    </a>
    <a href="{{ route('frontend.coaches.index') }}"
       class="{{ request()->routeIs('frontend.coaches.*', 'frontend.breeders.*', 'frontend.clinics.*') ? 'is-active' : '' }}">
        <span><i class="fas fa-users"></i></span>کاربران
    </a>
    <a href="{{ route('frontend.home') }}#services"
       class="{{ request()->routeIs('frontend.home', 'frontend.booking.*') ? 'is-active' : '' }}">
        <span><i class="fas fa-concierge-bell"></i></span>خدمات
    </a>
    <a href="{{ route('frontend.stores.index') }}"
       class="{{ request()->routeIs('frontend.stores.*', 'frontend.products.*', 'frontend.dog.supply.*', 'frontend.dog-supply.*') ? 'is-active' : '' }}">
        <span><i class="fas fa-store"></i></span>فروشگاه
    </a>
    <a href="{{ route('frontend.support.index') }}"
       class="{{ request()->routeIs('frontend.support.*', 'frontend.contact.*') ? 'is-active' : '' }}">
        <span><i class="fas fa-headset"></i></span>تیکت
    </a>
</nav>