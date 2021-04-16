<?php

namespace App\Http\Middleware;

use App\Models\Helpers\RateLimiter;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ThrottleRequestsInSeconds
 *
 * @package App\Http\Middleware
 */
class ThrottleRequestsInSeconds
{
    /**
     * @var RateLimiter
     */
    private RateLimiter $rateLimiter;

    /**
     * ThrottleRequestsInSeconds constructor.
     *
     * @param \App\Models\Helpers\RateLimiter $rateLimiter
     */
    public function __construct(RateLimiter $rateLimiter)
    {
        $this->rateLimiter = $rateLimiter;
    }

    public function handle(
        Request $request,
        Closure $next,
        $decaySeconds = 1,
        $prefix = ''
    ): Response {
        $key = sha1($prefix . $request->route()->getDomain() . '|' . $request->ip());

        $this->rateLimiter->setLimitSeconds($decaySeconds)
            ->throttle(
                $key,
                static function () use (&$response, $next, $request) : void {
                    $response = $next($request);
                }
            );

        return $response;
    }
}
