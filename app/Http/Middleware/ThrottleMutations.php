<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies a named throttle only to mutating HTTP verbs.
 */
class ThrottleMutations
{
    public function __construct(private ThrottleRequests $throttle)
    {
    }

    public function handle(Request $request, Closure $next, string $limiter = 'admin-mutations'): Response
    {
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return $next($request);
        }

        return $this->throttle->handle($request, $next, $limiter);
    }
}
