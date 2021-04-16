<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait UsesUUID
 *
 * @package App\Traits
 */
trait UsesUUID
{
    /**
     * Set uuid field
     */
    protected static function bootUsesUuid(): void
    {
        static::creating(static function ($model): void {
            if (!$model->getKey()) {
                $model->{$model->getUUIDFieldName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * @return string
     */
    protected function getUUIDFieldName(): string
    {
        return 'uuid';
    }
}
