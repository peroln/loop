<?php

declare(strict_types=1);

namespace App\Services\Base;

use App\Repositories\Base\Repository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class BaseModelService
 *
 * @package App\Services\Base
 */
abstract class BaseModelService implements ServiceInterface
{
    /**
     * @var Repository
     */
    protected Repository $model;

    /**
     * BaseModelService constructor.
     *
     * @param Repository $model
     */
    public function __construct(Repository $model)
    {
        $this->model = $model;
    }

    /**
     * @param       $attribute
     * @param       $value
     * @param array $columns
     * @param array $relations
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public function findBy($attribute, $value, $columns = ['*'], $relations = ['*']): Collection
    {
        return $this->model->findBy($attribute, $value, $columns, $relations);
    }

    /**
     * @param string[] $columns
     * @param array    $relations
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function all($columns = ['*'], $relations = ['*']): Collection
    {
        return $this->model->all($columns, $relations);
    }

    /**
     * @param       $id
     * @param array $columns
     * @param array $relations
     * @param int   $currentPage
     * @param int   $perPage
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function get($id, $columns = ['*'], $relations = ['*'], $currentPage = 1, $perPage = 15): ?Model
    {
        return $this->model->find($id, $columns, $relations);
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    /**
     * @param array $data
     * @param int   $id
     *
     * @return mixed
     */
    public function update(array $data, int $id): bool
    {
        return $this->model->update($data, $id);
    }

    /**
     * @param int      $perPage
     * @param string[] $columns
     * @param array    $relations
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
     * @throws \ReflectionException
     */
    public function paginate($perPage = 15, $columns = ['*'], $relations = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns, $relations);
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @param int      $id
     * @param string[] $columns
     * @param array    $relations
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function find(int $id, $columns = ['*'], $relations = ['*']): ?Model
    {
        return $this->model->find($id, $columns, $relations);
    }

    /**
     * @param int      $id
     * @param string[] $columns
     * @param string[] $relations
     *
     * @return Model|null
     */
    public function findOrFail(int $id, $columns = ['*'], $relations = ['*']): ?Model
    {
        return $this->model->findOrFail($id, $columns, $relations);
    }

    /**
     * Attention!  I do not recommended use it
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery(): Builder
    {
        return $this->model->newQuery();
    }
}
