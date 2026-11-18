<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name') }}</title>
    @vite(['resources/css/app-front.css'])
</head>
<body class="sp-body">
    <header class="sp-nav">
        <a class="sp-brand" href="{{ route('frontend.home') }}">
            <span class="sp-brand__fa">سپاسیران</span>
            <span class="sp-brand__en latin">Sepasiran</span>
        </a>
        <div class="sp-nav__actions">
            <a class="btn btn-ghost" href="{{ route('frontend.home') }}">بازگشت</a>
        </div>
    </header>

    @yield('main')

    @vite(['resources/js/app-front.js'])
</body>
</html>
