<?php

// app/Http/Middleware/IsAdmin.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle($request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        return redirect()->route('login')
            ->with('error', 'دسترسی غیرمجاز. لطفاً وارد شوید.');
    }
}
