<?php

namespace App\Http\Middleware;

use Closure;

class IsUser
{
    /* Handle an incoming request as user.*/
    public function handle($request, Closure $next)
    {
        if (auth()->user()->role != 0) {
            return abort(404);
        }

        return $next($request);
    }
}
