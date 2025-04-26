<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name') }}</title>

    {{-- فونت فارسی (اختیاری) --}}
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">

    {{-- استایل‌ها --}}
    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: #f4f6f9;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">

@yield('main')
{{--<div class="w-100 px-3" style="max-width: 720px;">--}}
{{--    <div class="card shadow border-0">--}}
{{--        <div class="card-header text-center bg-white border-0">--}}
{{--            <a href="/" class="h2 text-primary text-decoration-none">{{ config('app.name') }}</a>--}}
{{--        </div>--}}
{{--        <div class="card-body">--}}
{{--            @yield('main')--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

@vite(['resources/js/app.js'])
</body>

</html>
