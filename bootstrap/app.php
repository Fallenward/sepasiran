<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
    // می‌توانید یک مسیر واحد یا آرایه‌ای از مسیرها بدهید
        web : [
            __DIR__ . '/../routes/web.php',   // مسیرهای اصلی وب
            __DIR__ . '/../routes/auth.php',  // مسیرهای احراز هویت (login, logout, …)
        ],
        // اگر فایل api دارید همین‌جا اضافه کنید، در غیر این صورت null بماند
        // api : __DIR__ . '/../routes/api.php',
        health : '/up',                       // مسیر سلامت
    )
    ->withMiddleware(function (Middleware $middleware) {

        /* آلیاس‌های میان‌افزار مسیری
           -------------------------------- */
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            // مثال: 'auth'   => \Illuminate\Auth\Middleware\Authenticate::class,
        ]);

        /* افزودن به گروه‌های موجود (اختیاری)
           -------------------------------- */
        // $middleware->appendToGroup('web', \App\Http\Middleware\IsAdmin::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // اینجا می‌توانید هندلرهای استثنا را پیکربندی کنید
    })
    ->create();
