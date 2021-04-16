<?php

namespace App\Http\Middleware\Auth;

use App\Exceptions\Http\BadRequestException;
use App\Facades\DeviceFacade;
use Closure;
use Illuminate\Http\Request;

/**
 * Class CheckDevice
 *
 * @package App\Http\Middleware
 */
class CheckDevice
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('deviceId') && \DeviceFacade::isVerified($request->header('deviceId'))) {
            return $next($request);
        }
        throw new BadRequestException("Current device is not verified or not defined in headers");
    }
}
