@php
    $authUser = auth()->user();
    $accountLinks = [];
    $headerNotifications = collect();
    $unreadCount = 0;
    if ($authUser) {
        if (!empty($authUser->is_admin) || (method_exists($authUser, 'hasRole') && ($authUser->hasRole('admin') || $authUser->hasRole('administrator')))) {
            $accountLinks[] = ['label' => 'پنل ادمین', 'url' => route('admin.dashboard.index')];
        }
        if (method_exists($authUser, 'hasRole') && $authUser->hasRole('breeder')) {
            $accountLinks[] = ['label' => 'داشبورد پرورش‌دهنده', 'url' => route('breeder.dashboard')];
            $accountLinks[] = ['label' => 'افزودن سگ', 'url' => route('breeder.dashboard-create')];
            $accountLinks[] = ['label' => 'لیست سگ‌های من', 'url' => route('breeder.dogs.listing')];
        }
        if (method_exists($authUser, 'hasRole') && $authUser->hasRole('coach')) {
            $accountLinks[] = ['label' => 'داشبورد مربی', 'url' => route('coaches.dashboard')];
        }
        if (method_exists($authUser, 'hasRole') && $authUser->hasRole('clinic')) {
            $accountLinks[] = ['label' => 'داشبورد کلینیک', 'url' => route('clinic.dashboard')];
        }
        $accountLinks[] = ['label' => 'داشبورد من', 'url' => route('user.dashboard')];
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

        $headerNotifications = \App\Models\Notification::query()
            ->where('user_id', $authUser->id)
            ->latest()
            ->limit(6)
            ->get();
        $unreadCount = \App\Models\Notification::query()
            ->where('user_id', $authUser->id)
            ->whereNull('read_at')
            ->count();
    }
@endphp
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
            <a class="btn btn-ghost" href="{{ route('login') }}">ورود</a>
            <a class="btn btn-primary" href="{{ route('register') }}">ثبت‌نام</a>
        @else
            <div class="sp-nav__notify">
                <button type="button" class="sp-nav__notify-btn" aria-haspopup="true" aria-expanded="false" aria-label="اعلان‌ها">
                    <i class="fas fa-bell"></i>
                    @if($unreadCount > 0)
                        <span class="sp-nav__notify-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </button>
                <div class="sp-menu__dropdown sp-nav__notify-menu" role="menu">
                    <div class="sp-nav__notify-head">
                        <strong>اعلان‌ها</strong>
                        <a href="{{ route('account.notifications.index') }}">مشاهده همه</a>
                    </div>
                    @forelse($headerNotifications as $note)
                        <a class="sp-nav__notify-item {{ $note->read_at ? '' : 'is-unread' }}" href="{{ route('account.notifications.index') }}">
                            <span class="sp-nav__notify-title">{{ $note->title ?: 'اعلان' }}</span>
                            <span class="sp-nav__notify-msg">{{ \Illuminate\Support\Str::limit($note->message, 72) }}</span>
                        </a>
                    @empty
                        <p class="sp-nav__notify-empty">اعلان جدیدی نیست.</p>
                    @endforelse
                    @if($unreadCount > 0)
                        <form action="{{ route('account.notifications.read-all') }}" method="POST" class="sp-nav__notify-foot">
                            @csrf
                            <button type="submit">خواندن همه</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="sp-nav__account">
                <button type="button" class="btn btn-ghost sp-nav__account-btn" aria-haspopup="true" aria-expanded="false">
                    @if($authUser->avatarUrl())
                        <img class="sp-nav__account-avatar" src="{{ $authUser->avatarUrl() }}" alt="">
                    @endif
                    حساب من
                </button>
                <div class="sp-menu__dropdown sp-nav__account-menu" role="menu">
                    <div class="sp-nav__account-name">
                        {{ trim(($authUser->name ?? '').' '.($authUser->family ?? '')) ?: 'کاربر' }}
                    </div>
                    @foreach($accountLinks as $link)
                        <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                    @endforeach
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-primary">خروج</button>
            </form>
        @endguest
    </div>
</header>
