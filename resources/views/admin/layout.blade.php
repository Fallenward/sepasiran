<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
{{--    <link rel="icon" href="{{ asset($settings->fav_icon) }}">--}}
    <link rel="icon" href="#">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    @vite(['resources/css/app.css'])

    @stack('styles')
</head>

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5.min.css" rel="stylesheet" />
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Bundle شامل Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- یا اگر از AdminLTE استفاده می‌کنی: -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

@endsection

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.dashboard.index') }}" class="nav-link">داشبورد</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ms-auto">
                <!-- Search Icon -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>


                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-user"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">پروفایل</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            خروج
                        </a>
                        <form id="logout-form" action="#" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>

                <!-- Fullscreen -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>


    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="javascript:void(0);" class="brand-link">

            <div class="brand-text font-weight-light">
                <img class="img-fluid w-50 me-2" src="#" alt="{{env('APP_NAME')}}"/>
            </div>

        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    @php
                        $menus = [
                            ['route' => 'admin.dashboard.index', 'icon' => 'fas fa-tachometer-alt', 'text' => __('داشبورد')],
                            ['route' => 'sliders.index', 'icon' => 'fas fa-sliders-h', 'text' => __('اسلایدرها')],
                            ['route' => 'categories.index', 'icon' => 'fas fa-list', 'text' => __('دسته‌بندی‌ها')],
                            ['route' => 'courses.index', 'icon' => 'fas fa-book', 'text' => __('دوره‌ها')],
                            ['route' => 'blogs.index', 'icon' => 'fas fa-blog', 'text' => __('وبلاگ‌ها')],
                            ['route' => 'discounts.index', 'icon' => 'fas fa-tag', 'text' => __('تخفیف‌ها')],
                            ['route' => 'transactions.index', 'icon' => 'fas fa-money-bill-wave', 'text' => __('تراکنش‌ها')],
                        ];
                    @endphp

                    @foreach ($menus as $menu)
                        <li class="nav-item">
                            <a href="{{ route($menu['route']) }}" class="nav-link {{ request()->routeIs($menu['route']) ? 'active' : '' }}">
                                <i class="nav-icon {{ $menu['icon'] }}"></i>
                                <p>{{ $menu['text'] }}</p>
                            </a>
                        </li>
                    @endforeach

                    <li class="nav-item mt-3">
                        <a href="javascript:void(0);" id="logout-button" class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>{{ __('خروج') }}</p>
                        </a>
                        <form id="logout-form" class="d-none" action="#" method="POST">
                            @csrf
                        </form>
                    </li>
                </ul>

            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="mt-5"></div>
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2024 <a
                href="{{ route('admin.dashboard.index') }}">{{ config('app.name') }}</a>.</strong>
        <span>{{ __('All rights reserved.') }}</span>
    </footer>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('notificationToggle');
        const menu = document.getElementById('notificationsMenu');

        // رویداد کلیک روی دکمه آیکن زنگوله
        toggle.addEventListener('click', function(event) {
            event.preventDefault(); // جلوگیری از باز شدن لینک به #
            // اگر منو پنهان است، نمایش داده شود؛ و اگر نمایش داده شده، بسته شود
            if (menu.style.display === 'none' || menu.style.display === '') {
                menu.style.display = 'block';
            } else {
                menu.style.display = 'none';
            }
        });

        // بستن منو در صورت کلیک بیرون از Dropdown
        document.addEventListener('click', function(e) {
            // اگر کلیک روی خود دکمه یا درون منو نبود:
            if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    });
</script>

<script>
    window.jQuery = null;
    window.$ = null;
</script>

@vite(['resources/js/app.js'])

<!-- jQuery first -->
<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>



<!-- Clockpicker and select2 -->
<link href="{{asset('assets/css/bootstrap-clockpicker.min.css')}}" rel="stylesheet"/>
<script src="{{asset('assets/js/bootstrap-clockpicker.min.js')}}"></script>
<link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet"/>
<script src="{{asset('assets/js/select2.min.js')}}"></script>


<!-- Persian Datepicker -->
<link rel="stylesheet" href="{{asset('assets/css/persian-datepicker.min.css')}}">
<script src="{{asset('assets/js/persian-date.min.js')}}"></script>
<script src="{{asset('assets/js/persian-datepicker.min.js')}}"></script>


<!-- CKEditor -->
<script src="{{asset('assets/js/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/sweetalert2@11.js')}}"></script>






@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("logout-button").addEventListener("click", function () {
                Swal.fire({
                    title: "خروج از حساب",
                    text: "آیا مطمئن هستید که می‌خواهید از حساب خود خارج شوید؟",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "بله، خروج",
                    cancelButtonText: "لغو"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById("logout-form").submit();
                    }
                });
            });
        });
    </script>
@endpush



<script>
    if (document.querySelector('.editor')) {
        ClassicEditor
            .create(document.querySelector('.editor'), {
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'alignment', '|',
                    'heading', 'imageUpload', 'insertTable', 'mediaEmbed', 'codeBlock', '|', 'fontSize', 'fontFamily', 'fontColor', 'highlight', 'code', '|', 'undo', 'redo'
                ],
                language: 'fa', // Set to 'fa' for Persian language
                image: {
                    toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side', '|', 'imageResize:25', 'imageResize:50', 'imageResize:75']
                },
                simpleUpload: {
                    uploadUrl: '/upload-image', // Your upload image endpoint
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for Laravel security
                    }
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                }
            })
            .catch(error => {
                console.error(error);
            });
    }
    $(document).ready(function() {
        setTimeout(function() {
            $("#success-alert").fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000); // ناپدید شدن بعد از 3 ثانیه

        $(".datepicker").pDatepicker({
            format: 'YYYY/MM/DD',
            observer: true,
            autoClose: true,
            initialValueType: 'gregorian'
        });

    });

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('imagePreview');
            preview.src = reader.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

</script>



@push('scripts')
    <script src="{{ asset('js/upload.js') }}" defer></script>

@endpush

@stack('scripts')

<!-- ✅ jQuery (اول باید بارگذاری شود) -->


<!-- ✅ DataTables CSS و JS -->
<link rel="stylesheet" href="{{asset('assets/css/jquery.dataTables.min.css')}}">
<script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>


<!-- Toastr -->
<link rel="stylesheet" href="{{asset('assets/css/toastr.min.css')}}">
<script src="{{asset('assets/js/toastr.min.js')}}"></script>

<!-- Persian Datepicker -->


</body>



</html>


