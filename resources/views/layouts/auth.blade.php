<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | سپاسیران</title>
    @vite(['resources/css/app-front.css'])
</head>
<body class="sp-body auth-body">
    <header class="sp-nav auth-nav">
        <a class="sp-brand" href="{{ route('frontend.home') }}">
            <span class="sp-brand__fa">سپاسیران</span>
            <span class="sp-brand__en latin">Sepasiran</span>
        </a>
        <div class="sp-nav__actions">
            @if (!request()->routeIs('login'))
                <a class="btn btn-ghost" href="{{ route('login') }}">ورود</a>
            @endif
            @if (!request()->routeIs('register') && Route::has('register'))
                <a class="btn btn-primary" href="{{ route('register') }}">ثبت‌نام</a>
            @endif
        </div>
    </header>

    <main class="auth-main">
        @yield('main')
    </main>

    @vite(['resources/js/app-front.js'])
    @stack('scripts')
</body>
</html>
