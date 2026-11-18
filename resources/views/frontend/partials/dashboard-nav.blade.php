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
            'label' => 'رزرو',
            'url' => route('frontend.booking.index'),
            'icon' => 'far fa-calendar-check',
            'active' => request()->routeIs('frontend.booking.*'),
        ],
    ];

    if ($navUser && method_exists($navUser, 'hasRole') && $navUser->hasRole('breeder')) {
        $links[] = [
            'label' => 'سگ‌های من',
            'url' => route('breeder.dogs.listing'),
            'icon' => 'fas fa-dog',
            'active' => request()->routeIs('breeder.dogs.*'),
        ];
        $links[] = [
            'label' => 'افزودن سگ',
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
