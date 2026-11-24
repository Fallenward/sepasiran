<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'پنل مدیریت') — {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/fav.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/persian-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed sp-admin">
@php
    $user = auth()->user();
    $navGroups = [
        [
            'label' => 'عملیات',
            'items' => [
                ['route' => 'admin.dashboard.index', 'match' => 'admin.dashboard.*', 'icon' => 'fas fa-gauge-high', 'text' => 'داشبورد'],
                ['route' => 'admin.support-tickets.index', 'match' => 'admin.support-tickets.*', 'icon' => 'fas fa-headset', 'text' => 'تیکت‌های پشتیبانی'],
                ['route' => 'admin.support-messages.index', 'match' => 'admin.support-messages.*', 'icon' => 'fas fa-inbox', 'text' => 'پیام‌های تماس/پناهگاه'],
                ['route' => 'admin.bookings.index', 'match' => 'admin.bookings.*', 'icon' => 'fas fa-calendar-check', 'text' => 'رزروها'],
                ['route' => 'admin.transactions.index', 'match' => 'admin.transactions.*', 'icon' => 'fas fa-money-bill-wave', 'text' => 'تراکنش‌ها'],
            ],
        ],
        [
            'label' => 'کاتالوگ',
            'items' => [
                ['route' => 'admin.dogs.index', 'match' => 'admin.dogs.*', 'icon' => 'fas fa-dog', 'text' => 'سگ‌ها'],
                ['route' => 'admin.products.index', 'match' => 'admin.products.*', 'icon' => 'fas fa-store', 'text' => 'محصولات'],
                ['route' => 'admin.courses.index', 'match' => 'admin.courses.*', 'icon' => 'fas fa-book', 'text' => 'دوره‌ها'],
                ['route' => 'admin.insurances.index', 'match' => 'admin.insurances.*', 'icon' => 'fas fa-shield-alt', 'text' => 'بیمه'],
                ['route' => 'admin.breeds.index', 'match' => 'admin.breeds.*', 'icon' => 'fas fa-paw', 'text' => 'نژادها'],
            ],
        ],
        [
            'label' => 'افراد',
            'items' => [
                ['route' => 'admin.users.index', 'match' => 'admin.users.*', 'icon' => 'fas fa-users', 'text' => 'کاربران'],
                ['route' => 'admin.coaches.index', 'match' => 'admin.coaches.*', 'icon' => 'fas fa-chalkboard-teacher', 'text' => 'مربی‌ها'],
                ['route' => 'admin.roles.index', 'match' => 'admin.roles.*', 'icon' => 'fas fa-user-shield', 'text' => 'نقش‌ها'],
                ['route' => 'admin.permissions.index', 'match' => 'admin.permissions.*', 'icon' => 'fas fa-key', 'text' => 'مجوزها'],
            ],
        ],
        [
            'label' => 'محتوا',
            'items' => [
                ['route' => 'admin.blogs.index', 'match' => 'admin.blogs.*', 'icon' => 'fas fa-blog', 'text' => 'وبلاگ'],
                ['route' => 'admin.sliders.index', 'match' => 'admin.sliders.*', 'icon' => 'fas fa-images', 'text' => 'اسلایدرها'],
            ],
        ],
    ];
@endphp
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-dark sp-admin-topbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="منو"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-md-inline-block">
                <a href="{{ route('admin.dashboard.index') }}" class="nav-link">داشبورد</a>
            </li>
        </ul>

        <form class="form-inline ml-3 d-none d-lg-flex sp-admin-search" action="{{ route('admin.support-tickets.index') }}" method="get" role="search">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" name="q" placeholder="جستجوی تیکت…" value="{{ request('q') }}" aria-label="جستجو">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>

        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('frontend.home') }}" target="_blank" title="مشاهده سایت"><i class="fas fa-external-link-alt"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.support-tickets.index') }}" title="تیکت‌ها" id="admin-support-alarm">
                    <i class="fas fa-headset"></i>
                    <span class="badge badge-danger navbar-badge" id="admin-support-badge" style="display:none">0</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="adminUserMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                    <span class="d-none d-sm-inline">{{ $user?->name ?? 'مدیر' }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="adminUserMenu">
                    <a class="dropdown-item" href="{{ route('frontend.home') }}">سایت اصلی</a>
                    <div class="dropdown-divider"></div>
                    <button type="button" class="dropdown-item" id="logout-button">خروج</button>
                </div>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4 sp-admin-sidebar">
        <a href="{{ route('admin.dashboard.index') }}" class="brand-link text-center">
            <img src="{{ asset('images/fav.png') }}" alt="{{ config('app.name') }}" class="brand-image" style="max-height:36px;opacity:1">
            <span class="brand-text font-weight-bold">سپاسیران ادمین</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    @foreach($navGroups as $group)
                        <li class="nav-header">{{ $group['label'] }}</li>
                        @foreach($group['items'] as $item)
                            @if(\App\Helpers\GeneralHelpers::shouldShowMenu($item['route'], $user))
                                <li class="nav-item">
                                    <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                                        <i class="nav-icon {{ $item['icon'] }}"></i>
                                        <p>{{ $item['text'] }}</p>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endforeach
                    <li class="nav-header">حساب</li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link" id="sidebar-logout">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>خروج</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid sp-admin-content">
                @if(session('success'))
                    <div class="alert alert-success sp-admin-flash" id="success-alert">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger sp-admin-flash">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="main-footer sp-admin-footer">
        <strong>{{ config('app.name') }}</strong>
        <span class="text-muted">پنل مدیریت</span>
    </footer>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/persian-date.min.js') }}"></script>
<script src="{{ asset('assets/js/persian-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/ckeditor.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2@11.js') }}"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const logoutForm = document.getElementById('logout-form');
  function confirmLogout(e) {
    e.preventDefault();
    if (!logoutForm) return;
    if (window.Swal) {
      Swal.fire({
        title: 'خروج از حساب',
        text: 'مطمئن هستید؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'بله، خروج',
        cancelButtonText: 'لغو',
        confirmButtonColor: '#d33'
      }).then((r) => { if (r.isConfirmed) logoutForm.submit(); });
    } else {
      logoutForm.submit();
    }
  }
  document.getElementById('logout-button')?.addEventListener('click', confirmLogout);
  document.getElementById('sidebar-logout')?.addEventListener('click', confirmLogout);

  if (window.jQuery) {
    $('.select2').select2({ placeholder: 'انتخاب کنید', width: '100%', dir: 'rtl', allowClear: true });
    $('.datepicker').pDatepicker({ format: 'YYYY/MM/DD', observer: true, autoClose: true, initialValueType: 'gregorian' });
  }

  if (document.querySelector('.editor') && window.ClassicEditor) {
    ClassicEditor.create(document.querySelector('.editor'), { language: 'fa' }).catch(console.error);
  }

  const flash = document.getElementById('success-alert');
  if (flash) setTimeout(() => flash.remove(), 3500);
});

(function () {
  const badge = document.getElementById('admin-support-badge');
  if (!badge) return;
  let known = 0;
  async function poll() {
    try {
      const res = await fetch(@json(route('admin.support-tickets.poll')), { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
      const data = await res.json();
      const unread = Number(data.unread || 0);
      if (unread > 0) {
        badge.style.display = 'inline';
        badge.textContent = unread > 99 ? '99+' : String(unread);
        if (unread > known && window.toastr) toastr.warning('تیکت پشتیبانی جدید', 'هشدار');
      } else {
        badge.style.display = 'none';
      }
      known = unread;
    } catch (e) {}
  }
  poll();
  setInterval(poll, 5000);
})();
</script>
@stack('scripts')
</body>
</html>