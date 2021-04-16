<?php

namespace App\Http\Middleware\Auth;

use Closure;

class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param null                     $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($guard) {
            auth()->guard($guard);
        }

        return $next($request);
    }
}
