<?php

namespace App\Http\Requests\Traits;

/**
 * Get All Trait
 */
trait RequestAllTrait
{
    public function all($keys = null): array
    {
        return array_merge(parent::all(), $this->route()->parameters);
    }
}
