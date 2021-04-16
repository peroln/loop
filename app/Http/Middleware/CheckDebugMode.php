<?php

namespace App\Http\Middleware;

use App\Exceptions\Http\BadRequestException;
use Closure;

/**
 * Class CheckDebugMode
 * Check is app in debug mode
 *
 * @package App\Http\Middleware
 */
class CheckDebugMode
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     * @throws BadRequestException
     */
    public function handle($request, Closure $next)
    {
        if (config('app.debug')) {
            return $next($request);
        }

        $appName = config('app.name');
        throw new BadRequestException("The ${appName} is in production mode");
    }
}
