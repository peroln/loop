<?php

namespace App\Models\Helpers;

use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Cache\RateLimiter as BaseRateLimiter;

class RateLimiter
{
    use InteractsWithTime;

    protected BaseRateLimiter $limiter;

    protected int $maxAttempts;

    protected int $decaySeconds;

    public function __construct(BaseRateLimiter $limiter, int $maxAttempts = 1, int $decaySeconds = 180)
    {
        $this->limiter = $limiter;
        $this->maxAttempts = $maxAttempts;
        $this->decaySeconds = $decaySeconds;
    }

    public function throttle(string $key, \Closure $callback): void
    {
        if ($this->limiter->tooManyAttempts($key, $this->maxAttempts)) {
            throw new ThrottleRequestsException('Too Many Attempts. Time left_' . $this->limiter->availableIn($key));
        }

        $callback();

        $this->limiter->hit($key, $this->decaySeconds);
    }

    /**
     * @param int $seconds
     * @return $this
     */
    public function setLimitSeconds(int $seconds): RateLimiter
    {
        $this->decaySeconds = $seconds;

        return $this;
    }
}
