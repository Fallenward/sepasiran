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

    <nav class="sp-menu" aria-label="منوی اصلی">
        <ul class="sp-menu__list">
            <li class="sp-menu__item sp-menu__item--link">
                <a class="sp-menu__trigger {{ request()->routeIs('frontend.home') ? 'is-active' : '' }}"
                   href="{{ route('frontend.home') }}">خانه</a>
            </li>

            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger {{ request()->routeIs('frontend.coaches.*', 'frontend.breeders.*', 'frontend.clinics.*') ? 'is-active' : '' }}" aria-haspopup="true">کاربران</button>
                <div class="sp-menu__dropdown" role="menu">
                    <a href="{{ route('frontend.coaches.index') }}"
                       class="{{ request()->routeIs('frontend.coaches.*') ? 'is-active' : '' }}">مربی‌ها</a>
                    <a href="{{ route('frontend.breeders.index') }}"
                       class="{{ request()->routeIs('frontend.breeders.*') ? 'is-active' : '' }}">پرورش‌دهندگان</a>
                    <a href="{{ route('frontend.clinics.index') }}"
                       class="{{ request()->routeIs('frontend.clinics.*') ? 'is-active' : '' }}">کلینیک‌ها</a>
                </div>
            </li>

            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger {{ request()->routeIs('frontend.services.*', 'frontend.booking.*', 'frontend.clinic.insurance.*', 'frontend.online.courses', 'frontend.courses.*') ? 'is-active' : '' }}" aria-haspopup="true">خدمات</button>
                <div class="sp-menu__dropdown" role="menu">
                    <a href="{{ route('frontend.booking.index') }}"
                       class="{{ request()->routeIs('frontend.booking.index', 'frontend.booking.store', 'frontend.booking.success') ? 'is-active' : '' }}">رزرو خدمت</a>
                    <a href="{{ route('frontend.booking.open') }}"
                       class="{{ request()->routeIs('frontend.booking.open*') ? 'is-active' : '' }}">درخواست‌های باز</a>
                    <a href="{{ route('frontend.clinic.insurance.index') }}"
                       class="{{ request()->routeIs('frontend.clinic.insurance.*') ? 'is-active' : '' }}">بیمه</a>
                    <a href="{{ route('frontend.online.courses') }}"
                       class="{{ request()->routeIs('frontend.online.courses', 'frontend.courses.*') ? 'is-active' : '' }}">دوره‌ها</a>
                </div>
            </li>

            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger {{ request()->routeIs('frontend.dog.supply.*', 'frontend.dog-supply.*', 'frontend.stores.*', 'frontend.products.*', 'frontend.shelter.*') ? 'is-active' : '' }}" aria-haspopup="true">فروشگاه</button>
                <div class="sp-menu__dropdown" role="menu">
                    <a href="{{ route('frontend.dog.supply.index') }}"
                       class="{{ request()->routeIs('frontend.dog.supply.*', 'frontend.dog-supply.*') ? 'is-active' : '' }}">سگ‌ها</a>
                    <a href="{{ route('frontend.stores.index') }}"
                       class="{{ request()->routeIs('frontend.stores.*', 'frontend.products.*') ? 'is-active' : '' }}">محصولات</a>
                    <a href="{{ route('frontend.shelter.index') }}"
                       class="{{ request()->routeIs('frontend.shelter.*') ? 'is-active' : '' }}">پناهگاه</a>
                </div>
            </li>

            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger {{ request()->routeIs('frontend.news.*', 'frontend.blogs.*', 'frontend.blog.*') ? 'is-active' : '' }}" aria-haspopup="true">اخبار و وبلاگ</button>
                <div class="sp-menu__dropdown" role="menu">
                    <a href="{{ route('frontend.news.index') }}"
                       class="{{ request()->routeIs('frontend.news.*') ? 'is-active' : '' }}">اخبار</a>
                    <a href="{{ route('frontend.blogs.index') }}"
                       class="{{ request()->routeIs('frontend.blogs.*', 'frontend.blog.*') ? 'is-active' : '' }}">وبلاگ</a>
                </div>
            </li>

            <li class="sp-menu__item">
                <button type="button" class="sp-menu__trigger {{ request()->routeIs('frontend.support.*', 'frontend.support-web.*', 'frontend.contact.*') ? 'is-active' : '' }}" aria-haspopup="true">تیکت و تماس</button>
                <div class="sp-menu__dropdown" role="menu">
                    <a href="{{ route('frontend.support.index') }}"
                       class="{{ request()->routeIs('frontend.support.*') ? 'is-active' : '' }}">تیکت / پشتیبانی</a>
                    <a href="{{ route('frontend.contact.index') }}"
                       class="{{ request()->routeIs('frontend.contact.*') ? 'is-active' : '' }}">تماس با ما</a>
                    <a href="{{ route('frontend.about') }}"
                       class="{{ request()->routeIs('frontend.about') ? 'is-active' : '' }}">درباره ما</a>
                </div>
            </li>
        </ul>
    </nav>

    <div class="sp-nav__actions">
        @guest
            <a class="btn btn-ghost sp-nav__hide-sm" href="{{ route('login') }}">ورود</a>
            <a class="btn btn-primary sp-nav__hide-sm" href="{{ route('register') }}">ثبت‌نام</a>
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

            <div class="sp-nav__account sp-nav__hide-sm" data-sp-account>
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
            <form action="{{ route('logout') }}" method="POST" class="m-0 sp-nav__hide-sm">
                @csrf
                <button type="submit" class="btn btn-primary">خروج</button>
            </form>
        @endguest

        <button type="button"
                class="sp-nav__menu-btn"
                data-sp-sheet-open
                aria-controls="spSiteSheet"
                aria-expanded="false">
            <i class="fas fa-bars" aria-hidden="true"></i>
            <span>منو</span>
        </button>
    </div>
</header>

<div class="sp-sheet" id="spSiteSheet" data-sp-sheet hidden>
    <button type="button" class="sp-sheet__scrim" data-sp-sheet-close aria-label="بستن"></button>
    <div class="sp-sheet__panel" role="dialog" aria-modal="true" aria-labelledby="spSheetTitle">
        <header class="sp-sheet__head">
            <div>
                <p class="sp-sheet__eyebrow">سپاسیران</p>
                <h2 id="spSheetTitle">منوی سایت</h2>
            </div>
            <button type="button" class="sp-sheet__close" data-sp-sheet-close aria-label="بستن منو">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </header>

        <div class="sp-sheet__body">
            <section class="sp-sheet__group">
                <h3>خانه</h3>
                <a href="{{ route('frontend.home') }}" class="{{ request()->routeIs('frontend.home') ? 'is-active' : '' }}">خانه</a>
            </section>

            <section class="sp-sheet__group">
                <h3>کاربران</h3>
                <a href="{{ route('frontend.coaches.index') }}">مربی‌ها</a>
                <a href="{{ route('frontend.breeders.index') }}">پرورش‌دهندگان</a>
                <a href="{{ route('frontend.clinics.index') }}">کلینیک‌ها</a>
            </section>

            <section class="sp-sheet__group">
                <h3>خدمات</h3>
                <a href="{{ route('frontend.booking.index') }}">رزرو خدمت</a>
                <a href="{{ route('frontend.booking.open') }}">درخواست‌های باز</a>
                <a href="{{ route('frontend.clinic.insurance.index') }}">بیمه</a>
                <a href="{{ route('frontend.online.courses') }}">دوره‌ها</a>
            </section>

            <section class="sp-sheet__group">
                <h3>فروشگاه</h3>
                <a href="{{ route('frontend.dog.supply.index') }}">سگ‌ها</a>
                <a href="{{ route('frontend.stores.index') }}">محصولات</a>
                <a href="{{ route('frontend.shelter.index') }}">پناهگاه</a>
            </section>

            <section class="sp-sheet__group">
                <h3>اخبار و وبلاگ</h3>
                <a href="{{ route('frontend.news.index') }}" class="{{ request()->routeIs('frontend.news.*') ? 'is-active' : '' }}">اخبار</a>
                <a href="{{ route('frontend.blogs.index') }}" class="{{ request()->routeIs('frontend.blogs.*', 'frontend.blog.*') ? 'is-active' : '' }}">وبلاگ</a>
            </section>

            <section class="sp-sheet__group">
                <h3>تیکت و تماس</h3>
                <a href="{{ route('frontend.support.index') }}">تیکت / پشتیبانی</a>
                <a href="{{ route('frontend.contact.index') }}">تماس با ما</a>
                <a href="{{ route('frontend.about') }}">درباره ما</a>
            </section>

            <section class="sp-sheet__group">
                <h3>حساب</h3>
                @guest
                    <a href="{{ route('login') }}">ورود</a>
                    <a href="{{ route('register') }}">ثبت‌نام</a>
                @else
                    @foreach($accountLinks as $link)
                        <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                    @endforeach
                @endguest
            </section>
        </div>

        <footer class="sp-sheet__foot">
            @guest
                <a class="sp-btn sp-btn--ghost" href="{{ route('login') }}">ورود</a>
                <a class="sp-btn sp-btn--primary" href="{{ route('register') }}">ثبت‌نام</a>
            @else
                <div class="sp-sheet__user">
                    @if(!empty($headerShell['avatar_url']))
                        <img src="{{ $headerShell['avatar_url'] }}" alt="">
                    @endif
                    <strong>{{ $headerShell['display_name'] }}</strong>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sp-btn sp-btn--ghost" style="width:100%;color:var(--sp-danger)">خروج از حساب</button>
                </form>
            @endguest
        </footer>
    </div>
</div>