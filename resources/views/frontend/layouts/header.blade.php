@php
    $authUser = auth()->user();
    $accountLinks = [];
    $headerShell = [
        'display_name' => 'کاربر',
        'avatar_url' => null,
        'unread' => 0,
        'notifications' => [],
    ];
    if ($authUser) {
        if (!empty($authUser->is_admin) || (method_exists($authUser, 'hasRole') && ($authUser->hasRole('admin') || $authUser->hasRole('administrator')))) {
            $accountLinks[] = ['label' => 'پنل ادمین', 'url' => route('admin.dashboard.index')];
        }
        $accountLinks[] = ['label' => 'داشبورد', 'url' => $authUser->dashboardRoute()];
        if (method_exists($authUser, 'hasRole') && $authUser->hasRole('breeder')) {
            $accountLinks[] = ['label' => 'افزودن سگ', 'url' => route('breeder.dashboard-create')];
            $accountLinks[] = ['label' => 'لیست سگ‌های من', 'url' => route('breeder.dogs.listing')];
        }
        $accountLinks[] = ['label' => 'ویرایش پروفایل', 'url' => route('account.profile.edit')];
        $accountLinks[] = ['label' => 'اعلان‌ها', 'url' => route('account.notifications.index')];
        $accountLinks[] = ['label' => 'رزرو خدمت', 'url' => route('frontend.booking.index')];
        if (\Illuminate\Support\Facades\Route::has('wallet.index')) {
            $accountLinks[] = ['label' => 'کیف پول', 'url' => route('wallet.index')];
        }
        $seen = [];
        $accountLinks = array_values(array_filter($accountLinks, function ($row) use (&$seen) {
            if (isset($seen[$row['url']])) {
                return false;
            }
            $seen[$row['url']] = true;
            return true;
        }));

        try {
            $headerShell = \App\Support\AppCache::userHeader($authUser);
        } catch (\Throwable) {
            $headerShell['display_name'] = trim(($authUser->name ?? '').' '.($authUser->family ?? '')) ?: 'کاربر';
            $headerShell['avatar_url'] = $authUser->avatarUrl();
        }
    }
    $headerNotifications = collect($headerShell['notifications'] ?? []);
    $unreadCount = (int) ($headerShell['unread'] ?? 0);
@endphp
<header class="sp-nav" data-sp-nav>
    <a class="sp-brand" href="{{ route('frontend.home') }}">
        <span class="sp-brand__fa">سپاسیران</span>
        <span class="sp-brand__en latin">Sepasiran</span>
    </a>

    <button type="button"
            class="sp-nav__burger"
            data-sp-nav-toggle
            aria-controls="spMainMenu"
            aria-expanded="false"
            aria-label="باز کردن منو">
        <span class="sp-nav__burger-lines" aria-hidden="true">
            <span></span><span></span><span></span>
        </span>
    </button>

    <div class="sp-nav__scrim" data-sp-nav-scrim hidden></div>

    <nav class="sp-menu" id="spMainMenu" aria-label="منوی اصلی" data-sp-menu>
        <div class="sp-menu__drawer-head">
            <strong>منو</strong>
            <button type="button" class="sp-nav__drawer-close" data-sp-nav-close aria-label="بستن منو">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <ul class="sp-menu__list">
            <li class="sp-menu__item sp-menu__item--link">
                <a class="sp-menu__trigger {{ request()->routeIs('frontend.home') ? 'is-active' : '' }}"
                   href="{{ route('frontend.home') }}">خانه</a>
            </li>
            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger" aria-haspopup="true" aria-expanded="false" data-sp-menu-section>سگ‌ها</button>
                <div class="sp-menu__dropdown" role="menu">
                    <a href="{{ route('frontend.dog.supply.index') }}"
                       class="{{ request()->routeIs('frontend.dog.supply.*') ? 'is-active' : '' }}">لیست سگ‌ها</a>
                    <a href="{{ route('frontend.breeders.index') }}">پرورش‌دهندگان</a>
                    <a href="{{ route('frontend.shelter.index') }}">پناهگاه</a>
                </div>
            </li>
            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger" aria-haspopup="true" aria-expanded="false" data-sp-menu-section>خدمات</button>
                <div class="sp-menu__dropdown" role="menu">
                    <a href="{{ route('frontend.booking.index') }}"
                       class="{{ request()->routeIs('frontend.booking.index', 'frontend.booking.store', 'frontend.booking.success') ? 'is-active' : '' }}">رزرو خدمت</a>
                    <a href="{{ route('frontend.booking.open') }}"
                       class="{{ request()->routeIs('frontend.booking.open*') ? 'is-active' : '' }}">درخواست‌های باز</a>
                    <a href="{{ route('frontend.services.index') }}">همه خدمات</a>
                    <a href="{{ route('frontend.coaches.index') }}">مربی‌ها</a>
                    <a href="{{ route('frontend.clinics.index') }}">کلینیک‌ها</a>
                    <a href="{{ route('frontend.clinic.insurance.index') }}">بیمه</a>
                    <a href="{{ route('frontend.online.courses') }}">دوره‌ها</a>
                </div>
            </li>
            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger" aria-haspopup="true" aria-expanded="false" data-sp-menu-section>فروشگاه</button>
                <div class="sp-menu__dropdown" role="menu">
                    <a href="{{ route('frontend.stores.index') }}"
                       class="{{ request()->routeIs('frontend.stores.*', 'frontend.products.*') ? 'is-active' : '' }}">محصولات</a>
                </div>
            </li>
            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger" aria-haspopup="true" aria-expanded="false" data-sp-menu-section>حساب</button>
                <div class="sp-menu__dropdown" role="menu">
                    @guest
                        <a href="{{ route('login') }}">ورود</a>
                        <a href="{{ route('register') }}">ثبت‌نام</a>
                    @else
                        @foreach($accountLinks as $link)
                            <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        @endforeach
                        <form action="{{ route('logout') }}" method="POST" class="sp-menu__logout">
                            @csrf
                            <button type="submit">خروج</button>
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
            <a class="btn btn-ghost sp-nav__desktop-only" href="{{ route('login') }}">ورود</a>
            <a class="btn btn-primary" href="{{ route('register') }}">ثبت‌نام</a>
        @else
            <div class="sp-nav__notify" data-sp-notify>
                <button type="button"
                        class="sp-nav__notify-btn"
                        data-sp-notify-toggle
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-label="اعلان‌ها">
                    <i class="fas fa-bell"></i>
                    <span class="sp-nav__notify-badge {{ $unreadCount > 0 ? '' : 'is-hidden' }}" data-sp-notify-badge>
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                </button>
                <div class="sp-menu__dropdown sp-nav__notify-menu" role="menu" data-sp-notify-menu>
                    <div class="sp-nav__notify-head">
                        <strong>اعلان‌ها</strong>
                        <a href="{{ route('account.notifications.index') }}" data-sp-notify-all>مشاهده همه</a>
                    </div>
                    <div class="sp-nav__notify-list" data-sp-notify-list>
                        @forelse($headerNotifications as $note)
                            <a class="sp-nav__notify-item {{ empty($note['read_at']) ? 'is-unread' : '' }}"
                               href="{{ $note['url'] }}"
                               data-sp-notify-item
                               data-id="{{ $note['id'] }}"
                               data-read-url="{{ route('account.notifications.read', $note['id']) }}"
                               data-unread="{{ empty($note['read_at']) ? '1' : '0' }}">
                                <span class="sp-nav__notify-title">{{ $note['title'] }}</span>
                                <span class="sp-nav__notify-msg">{{ $note['message'] }}</span>
                            </a>
                        @empty
                            <p class="sp-nav__notify-empty">اعلان جدیدی نیست.</p>
                        @endforelse
                    </div>
                    <div class="sp-nav__notify-foot">
                        <a class="sp-nav__notify-page" href="{{ route('account.notifications.index') }}">رفتن به صفحه اعلان‌ها</a>
                        <button type="button"
                                class="sp-nav__notify-readall {{ $unreadCount > 0 ? '' : 'is-hidden' }}"
                                data-sp-notify-readall
                                data-url="{{ route('account.notifications.read-all') }}">
                            خواندن همه
                        </button>
                    </div>
                </div>
            </div>

            <div class="sp-nav__account sp-nav__desktop-only" data-sp-account>
                <button type="button" class="btn btn-ghost sp-nav__account-btn" data-sp-account-toggle aria-haspopup="true" aria-expanded="false">
                    @if(!empty($headerShell['avatar_url']))
                        <img class="sp-nav__account-avatar" src="{{ $headerShell['avatar_url'] }}" alt="" data-sp-avatar>
                    @endif
                    حساب من
                </button>
                <div class="sp-menu__dropdown sp-nav__account-menu" role="menu">
                    <div class="sp-nav__account-name">
                        {{ $headerShell['display_name'] }}
                    </div>
                    @foreach($accountLinks as $link)
                        <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                    @endforeach
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0 sp-nav__desktop-only">
                @csrf
                <button type="submit" class="btn btn-primary">خروج</button>
            </form>
        @endguest
    </div>
</header>
