<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1.0, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', env('APP_NAME'))</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset($settings->fav_icon) }}">

    <!-- Bootstrap CSS -->
    @vite(['resources/css/app-front.css'])
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css" >
    <!-- Custom CSS -->
    @stack('styles')

</head>
<body>

<!-- Main Content -->
<div class="main-content">
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: '{{ session("success") }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif
    @yield('content')
</div>


<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" />
<script src="{{asset('assets/js/select2.min.js')}}"></script>
@vite(['resources/js/app-front.js'])
<!-- Custom Scripts -->

@yield('scripts')
</body>
</html>
