<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <link rel="icon" href="{{ asset(optional($settings)->fav_icon ?? 'images/fav.png') }}">

    @vite(['resources/css/app-front.css'])
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    @stack('styles')
</head>
<body class="sp-body sp-shell">
    @include('frontend.layouts.header')

    <div class="main-content">
        @if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire({
                        icon: 'success',
                        title: @json(session('success')),
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                });
            </script>
        @endif
        @yield('content')
    </div>

    @include('frontend.layouts.footer')
    @include('frontend.layouts.bottom-nav')

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet"/>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    @vite(['resources/js/app-front.js', 'resources/js/vue/main.js'])
    @yield('scripts')
</body>
</html>
