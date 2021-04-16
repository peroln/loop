<?php

declare(strict_types=1);

namespace App\Traits;

use App\Observers\ObserverInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait RegisterObserversClasses
 *
 * @package App\Traits
 */
trait RegisterObserversClasses
{
    /**
     * Get list observers for model
     *
     * @return array
     */
    protected function observers(): array
    {
        return [
            //Example
            //Model::class => ObserverInterface::class
        ];
    }

    /**
     * Register observers classes
     */
    protected function registerObservers(): void
    {
        foreach ($this->observers() as $model => &$observer) {
            if (is_subclass_of($model, Model::class) && is_subclass_of($observer, ObserverInterface::class)) {
                $model::observe($observer);
            } else {
                continue;
            }
        }
    }
}
