<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request as admin
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->role != 1) {
            return abort(404);
        }

        return $next($request);
    }
}
