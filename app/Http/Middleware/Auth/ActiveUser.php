<?php

namespace App\Http\Middleware\Auth;

use Auth;
use Closure;

class ActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user) {
            $user->last_activity = \Carbon\Carbon::now();
            $user->active = true;
            $user->save();
        }

        return $next($request);
    }
}
