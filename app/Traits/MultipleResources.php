<?php

namespace App\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Exception;

/**
 * Trait MultipleResources
 *
 * @package App\Traits
 */
trait MultipleResources
{
    /**
     * MultipleResources constructor.
     *
     * @param $resource
     * @param string $resourceClass
     * @throws \Exception
     */
    public function __construct($resource, string $resourceClass)
    {
        if ($this instanceof ResourceCollection) {
            if (is_subclass_of($resourceClass, JsonResource::class)) {
                $this->collects = $resourceClass;
            }
            parent::__construct($resource);
        } else {
            throw new Exception("Invalid parent class");
        }
    }
}
