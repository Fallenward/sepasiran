<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your App</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Optional Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/app-front.css'])
</head>
<body>

    {{-- Optional custom header component --}}
    <x-header />

    <!-- Start Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom shadow-sm mb-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <!-- Left -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">داشبورد</a>
                </li>
            </ul>

            <!-- Right -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-search"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"><i class="fas fa-user"></i> مهمان</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"><i class="fas fa-expand-arrows-alt"></i></a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->

    <!-- Page Content -->
    <div class="content container">
        @yield('content')
    </div>

</body>
</html>
