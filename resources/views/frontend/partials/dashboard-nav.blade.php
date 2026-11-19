@php
    $navUser = $user ?? auth()->user();
    $dashHome = $navUser ? $navUser->dashboardRoute() : route('user.dashboard');
    $links = [
        [
            'label' => 'خلاصه',
            'url' => $dashHome,
            'icon' => 'fas fa-th-large',
            'active' => request()->routeIs('user.dashboard', 'breeder.dashboard', 'coaches.dashboard', 'clinic.dashboard'),
        ],
        [
            'label' => 'رزروهای من',
            'url' => route('account.bookings.index'),
            'icon' => 'far fa-calendar-alt',
            'active' => request()->routeIs('account.bookings.*'),
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
            'label' => 'رزرو جدید',
            'url' => route('frontend.booking.index'),
            'icon' => 'far fa-calendar-plus',
            'active' => request()->routeIs('frontend.booking.*'),
        ],
    ];

    if ($navUser && method_exists($navUser, 'hasRole') && ($navUser->hasRole('coach') || $navUser->hasRole('clinic') || $navUser->hasRole('breeder'))) {
        $inboxUrl = route('account.reservations.index');
        if ($navUser->hasRole('coach') && \Illuminate\Support\Facades\Route::has('coaches.reservations')) {
            $inboxUrl = route('coaches.reservations');
        } elseif ($navUser->hasRole('clinic') && \Illuminate\Support\Facades\Route::has('clinic.reservations')) {
            $inboxUrl = route('clinic.reservations');
        } elseif ($navUser->hasRole('breeder') && \Illuminate\Support\Facades\Route::has('breeder.reservations')) {
            $inboxUrl = route('breeder.reservations');
        }

        array_splice($links, 1, 0, [[
            'label' => 'درخواست رزرو',
            'url' => $inboxUrl,
            'icon' => 'fas fa-inbox',
            'active' => request()->routeIs('account.reservations.*', 'coaches.reservations', 'clinic.reservations', 'breeder.reservations'),
        ]]);
    }

    if ($navUser && method_exists($navUser, 'hasRole') && $navUser->hasRole('coach')) {
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
@endphp
<nav class="sp-dash__nav" aria-label="ناوبری حساب">
    <div class="sp-dash__nav-track">
        @foreach($links as $link)
            <a href="{{ $link['url'] }}"
               class="sp-dash__nav-link {{ !empty($link['active']) ? 'is-active' : '' }}">
                <i class="{{ $link['icon'] }}" aria-hidden="true"></i>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
