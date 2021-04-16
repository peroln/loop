<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class RelationsManager
 *
 * @package App\Traits
 */
trait RelationsManager
{
    protected $modelName;

    protected $relationsList = [];

    protected $relationsInitialized = false;

    protected $relationClasses = [
        HasOne::class,
        HasMany::class,
        BelongsTo::class,
        BelongsToMany::class,
    ];

    /**
     * @return mixed
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @param mixed $modelName
     */
    public function setModelName($modelName): void
    {
        $this->modelName = $modelName;
    }

    /**
     * @param null $type
     * @return array
     * @throws \ReflectionException
     */
    public function getAllRelations($type = null): array
    {
        if (!$this->relationsInitialized) {
            $this->initAllRelations();
        }

        return $type ? ($this->relationsList[$type] ?? []) : $this->array_flatten($this->relationsList);
    }

    /**
     * @throws \ReflectionException
     */
    protected function initAllRelations()
    {
        $this->relationsInitialized = true;

        $reflect = new ReflectionClass($this->modelName);

        foreach ($reflect->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            /** @var ReflectionMethod $method */
            if ($method->hasReturnType() && in_array($method->getReturnType()->getName(), $this->relationClasses)) {
                $this->relationsList[$method->getReturnType()->getName()][] = $method->getName();
            }
        }
    }

    /**
     * @param array $items
     * @return mixed|null
     */
    private function array_flatten($items)
    {
        if (!is_array($items)) {
            return [$items];
        }

        return array_reduce($items, function ($carry, $item) {
            return array_merge($carry, $this->array_flatten($item));
        }, []);
    }
}
