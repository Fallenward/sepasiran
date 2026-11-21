<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/auth.php',
        ],
        // Uncomment if you have an API route file
        // api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'role'     => \App\Http\Middleware\RoleMiddleware::class,
            'throttle.mutations' => \App\Http\Middleware\ThrottleMutations::class,
        ]);

        // Payment gateways POST back without a CSRF token.
        // Logout is safe to skip: users must be able to leave even with a stale CSRF/session.
        $middleware->validateCsrfTokens(except: [
            'logout',
            'booking/pay/*/callback',
            'dog-order/*/callback',
        ]);

        // Optionally add middleware to groups
        // $middleware->appendToGroup('web', \App\Http\Middleware\RoleMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configure exception handlers here if needed
    })
    ->create();
