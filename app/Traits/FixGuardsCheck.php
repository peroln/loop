<?php

namespace App\Traits;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

/**
 * Trait FixGuardsCheck
 *
 * @package App\Traits
 */
trait FixGuardsCheck
{
    use AuthorizesRequests {
        authorize as protected baseAuth;
    }

    /**
     * Get app base guards
     *
     * @return array
     */
    protected function guards(): array
    {
        return [];
    }

    /**
     * @param $ability
     * @param array $arguments
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorize($ability, $arguments = []): void
    {
        foreach ($this->guards() as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::shouldUse($guard);
                break;
            }
        }
        $this->baseAuth($ability, $arguments);
    }
}
