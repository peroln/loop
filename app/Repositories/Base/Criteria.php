<?php

declare(strict_types=1);

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Model;

abstract class Criteria
{
    abstract public function apply(Model $model, Repository $repository);
}
