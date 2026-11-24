@php
    $navUser = $user ?? auth()->user();
    $dashHome = $navUser ? $navUser->dashboardRoute() : route('user.dashboard');
    $links = [
        [
            'label' => 'داشبورد',
            'url' => $dashHome,
            'icon' => 'fas fa-th-large',
            'active' => request()->routeIs('user.dashboard', 'breeder.dashboard', 'coaches.dashboard', 'clinic.dashboard'),
        ],
        [
            'label' => 'رزروها',
            'url' => route('account.bookings.index'),
            'icon' => 'far fa-calendar-alt',
            'active' => request()->routeIs('account.bookings.*', 'account.reservations.*', 'coaches.reservations', 'clinic.reservations', 'breeder.reservations'),
        ],
        [
            'label' => 'سگ‌های من',
            'url' => route('account.dogs.index'),
            'icon' => 'fas fa-paw',
            'active' => request()->routeIs('account.dogs.*'),
        ],
        [
            'label' => 'کیف پول',
            'url' => route('wallet.index'),
            'icon' => 'fas fa-wallet',
            'active' => request()->routeIs('wallet.*', 'coaches.wallet'),
        ],
        [
            'label' => 'پروفایل',
            'url' => route('account.profile.edit'),
            'icon' => 'far fa-user',
            'active' => request()->routeIs('account.profile.*'),
        ],
        [
            'label' => 'اعلان‌ها',
            'url' => route('account.notifications.index'),
            'icon' => 'fas fa-bell',
            'active' => request()->routeIs('account.notifications.*'),
        ],
        [
            'label' => 'مجوز بیمه',
            'url' => route('account.insurance-licenses.index'),
            'icon' => 'fas fa-shield-alt',
            'active' => request()->routeIs('account.insurance-licenses.*', 'frontend.clinic.insurance.*'),
        ],
        [
            'label' => 'دوره‌های من',
            'url' => route('account.course-enrollments.index'),
            'icon' => 'fas fa-play-circle',
            'active' => request()->routeIs('account.course-enrollments.*', 'frontend.courses.*', 'frontend.online.courses'),
        ],

        [
            'label' => 'رزرو جدید',
            'url' => route('frontend.booking.index'),
            'icon' => 'far fa-calendar-plus',
            'active' => request()->routeIs('frontend.booking.index', 'frontend.booking.store', 'frontend.booking.success'),
        ],
        [
            'label' => 'درخواست‌های باز',
            'url' => route('frontend.booking.open'),
            'icon' => 'fas fa-door-open',
            'active' => request()->routeIs('frontend.booking.open*'),
        ],
    ];

    if ($navUser && method_exists($navUser, 'hasRole') && $navUser->hasRole('coach')) {
        $links[] = [
            'label' => 'دوره‌ها',
            'url' => route('coaches.courses.index'),
            'icon' => 'fas fa-graduation-cap',
            'active' => request()->routeIs('coaches.courses.*'),
        ];
        $links[] = [
            'label' => 'فضای مربی',
            'url' => route('coaches.listing'),
            'icon' => 'fas fa-chalkboard-teacher',
            'active' => request()->routeIs('coaches.listing'),
        ];
    }

    if ($navUser && method_exists($navUser, 'hasRole') && $navUser->hasRole('breeder')) {
        $links[] = [
            'label' => 'آگهی سگ',
            'url' => route('breeder.dogs.listing'),
            'icon' => 'fas fa-dog',
            'active' => request()->routeIs('breeder.dogs.*'),
        ];
        $links[] = [
            'label' => 'افزودن آگهی',
            'url' => route('breeder.dashboard-create'),
            'icon' => 'fas fa-plus',
            'active' => request()->routeIs('breeder.dashboard-create', 'breeder.dashboard-store'),
        ];
    }

    $activeLink = collect($links)->firstWhere('active', true) ?? $links[0];
@endphp
<nav class="sp-dash__nav" aria-label="ناوبری حساب" data-sp-dash-nav>
    <div class="sp-dash__nav-track" aria-hidden="false">
        @foreach($links as $link)
            <a href="{{ $link['url'] }}"
               class="sp-dash__nav-link {{ !empty($link['active']) ? 'is-active' : '' }}">
                <i class="{{ $link['icon'] }}" aria-hidden="true"></i>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </div>

    <div class="sp-dash__nav-mobile">
        <button type="button"
                class="sp-dash__nav-current"
                data-sp-dash-nav-toggle
                aria-expanded="false"
                aria-controls="spDashNavSheet">
            <span class="sp-dash__nav-current-icon" aria-hidden="true"><i class="{{ $activeLink['icon'] }}"></i></span>
            <span class="sp-dash__nav-current-text">
                <small>بخش فعلی</small>
                <strong>{{ $activeLink['label'] }}</strong>
            </span>
            <i class="fas fa-chevron-down sp-dash__nav-caret" aria-hidden="true"></i>
        </button>

        <div class="sp-dash__nav-sheet" id="spDashNavSheet" data-sp-dash-nav-sheet hidden>
            <button type="button" class="sp-dash__nav-sheet-scrim" data-sp-dash-nav-close aria-label="بستن"></button>
            <div class="sp-dash__nav-sheet-panel" role="dialog" aria-modal="true" aria-label="بخش‌های حساب">
                <header class="sp-dash__nav-sheet-head">
                    <strong>جابه‌جایی در حساب</strong>
                    <button type="button" class="sp-dash__nav-sheet-close" data-sp-dash-nav-close aria-label="بستن">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                </header>
                <div class="sp-dash__nav-sheet-list">
                    @foreach($links as $link)
                        <a href="{{ $link['url'] }}"
                           class="sp-dash__nav-sheet-link {{ !empty($link['active']) ? 'is-active' : '' }}">
                            <i class="{{ $link['icon'] }}" aria-hidden="true"></i>
                            <span>{{ $link['label'] }}</span>
                            @if(!empty($link['active']))
                                <em>فعلی</em>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</nav>