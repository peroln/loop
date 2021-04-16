<?php

namespace App\Repositories\Base;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    // public function all($columns = ['*'], $relations = []): Collection;
    /**
     * @param array         $relations
     * @param               $orderBy
     * @param               $sorting
     * @param int|null      $size
     * @param string[]      $columns
     * @param callable|null $when
     *
     * @return mixed
     */
    public function all(
        array $relations = [],
        $orderBy = null,
        $sorting = null,
        int $size = null,
        array $columns = ['*'],
        callable $when = null
    );

    public function paginate($perPage = 15, $columns = ['*'], $relations = []): LengthAwarePaginator;

    public function create(array $data): ?Model;

    public function update(array $data, int $id);

    public function delete(int $id): bool;

    public function find($id, $columns = ['*']): ?Model;

    public function findBy($field, $value, $columns = ['*'], int $size = 0);
}
