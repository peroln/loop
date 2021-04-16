<?php

declare(strict_types=1);

namespace App\Services\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface ServiceInterface
 *
 * @package App\Services\Base
 */
interface ServiceInterface
{
    /**
     * @param string[] $columns
     * @param array    $relations
     *
     * @return mixed
     */
    public function all($columns = ['*'], $relations = []): Collection;

    /**
     * @param int      $perPage
     * @param string[] $columns
     * @param array    $relations
     *
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = ['*'], $relations = []): LengthAwarePaginator;

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data): Model;

    /**
     * @param array $data
     * @param       $id
     *
     * @return mixed
     */
    public function update(array $data, int $id): bool;

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete(int $id): bool;

    /**
     * @param          $id
     * @param string[] $columns
     *
     * @return mixed
     */
    public function find(int $id, $columns = ['*']): ?Model;

    /**
     * @param          $field
     * @param          $value
     * @param string[] $columns
     *
     * @return mixed
     */
    public function findBy($field, $value, $columns = ['*']): Collection;
}
