<?php

declare(strict_types=1);

namespace App\Repositories\Base;

use App\Exceptions\Application\RepositoryException;
use App\Traits\RelationsManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class Repository
 * @package App\Repositories\Base
 */
abstract class Repository implements CriteriaInterface
{
    use RelationsManager;

    /**
     * @var Application $app
     */
    private Application $app;

    /**
     * @var Model
     */
    public Model $model;

    /**
     * @var Collection
     */
    protected Collection $criteria;

    /**
     * @var bool
     */
    protected bool $skipCriteria;

    /**
     * Repository constructor.
     *
     * @param Application     $app
     * @param Collection|null $collection
     *
     * @throws \App\Exceptions\Application\RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Application $app, Collection $collection = null)
    {
        $this->app = $app;
        $this->criteria = $collection ?? new Collection();
        $this->resetScope();
        $this->makeModel();
        $this->setModelName($this->model());
    }

    /**
     * @return string
     */
    abstract public function model(): string;

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return Model
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function makeModel(): Model
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->model = $model;
    }

    /**
     * @description Load child model class
     *
     * @param string $childClass
     *
     * @return void
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function reMakeModel(string $childClass): void
    {
        if (!is_subclass_of($childClass, $this->model())) {
            throw new RepositoryException(
                "Class {$childClass} must be an instance of {$this->model()}"
            );
        }
        $model = $this->app->make($childClass);
        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        $this->model = $model;
    }

    /**
     * @param array         $relations
     * @param null          $orderBy
     * @param null          $sorting
     * @param int|null      $size
     * @param array         $columns
     * @param callable|null $when
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    public function all(
        array $relations = [],
        $orderBy = null,
        $sorting = null,
        int $size = null,
        array $columns = ['*'],
        callable $when = null
    ) {
        $query = $this->model->with($relations);

        if (null !== $orderBy && null !== $sorting) {
            $query->orderBy($orderBy, $sorting);
        }

        $query->when(is_callable($when), $when);

        return ($size !== null) ? $query->paginate($size, $columns) : $query->get($columns);
    }

    /**
     * @param int      $perPage
     * @param string[] $columns
     * @param array    $relations
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \ReflectionException
     */
    public function paginate($perPage = 15, $columns = ['*'], $relations = []): LengthAwarePaginator
    {
        $relations = $this->checkRelations($relations);

        return $this->model->newQuery()->with($relations)->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->newQuery()->create($data);
    }

    /**
     * @param array  $data
     * @param        $id
     * @param string $attribute
     *
     * @return int|bool
     */
    public function update(array $data, int $id, string $attribute = "id")
    {
        if (array_key_exists('id', $data)) {
            unset($data['id']);
        }

        return $this->model->newQuery()->where($attribute, '=', $id)->update($data);
    }

    /**
     * @param $id
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id): bool
    {
        return $this->model->newQuery()->findOrFail($id)->delete();
    }

    /**
     * @param          $id
     * @param string[] $columns
     * @param array    $relations
     * @param int      $perPage
     *
     * @return Model
     * @throws \ReflectionException
     */
    public function find($id, $columns = ['*'], $relations = [], $perPage = 15): ?Model
    {
        $relations = $this->checkRelations($relations);

        return $this->model->newQuery()->with($relations)->find($id, $columns);
    }

    public function findOrFail($id, $columns = ['*'], $relations = []): Model
    {
        $relations = $this->checkRelations($relations);

        return $this->model->newQuery()->with($relations)->findOrFail($id, $columns);
    }

    /**
     * @param          $field
     * @param          $value
     * @param string[] $columns
     * @param array    $relations
     *
     * @param null     $size
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Collection
     * @throws \ReflectionException
     */
    public function findBy($field, $value, $columns = ['*'], $relations = [], $size = null)
    {
        $relations = $this->checkRelations($relations);
        $query = $this->model->newQuery()->where($field, '=', $value)->with($relations);

        return ($size !== null) ? $query->paginate($size, $columns) : $query->get($columns);
    }

    /**
     * @param          $field
     * @param          $value
     * @param string[] $columns
     * @param array    $relations
     *
     * @return Model
     * @throws \ReflectionException
     */
    public function findByOrFail($field, $value, $columns = ['*'], $relations = []): Model
    {
        $relations = $this->checkRelations($relations);

        return $this->model->newQuery()->where($field, '=', $value)->with($relations)->firstOrFail($columns);
    }

    /**
     * @return $this
     */
    public function resetScope(): self
    {
        $this->skipCriteria(false);

        return $this;
    }

    /**
     * @param bool $status
     *
     * @return $this|CriteriaInterface
     */
    public function skipCriteria($status = true): CriteriaInterface
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCriteria(): Collection
    {
        return $this->criteria;
    }

    /**
     * @param Criteria $criteria
     *
     * @return $this|CriteriaInterface
     */
    public function getByCriteria(Criteria $criteria): CriteriaInterface
    {
        $this->model = $criteria->apply($this->model, $this);

        return $this;
    }

    /**
     * @param Criteria $criteria
     *
     * @return CriteriaInterface|void
     */
    public function pushCriteria(Criteria $criteria): CriteriaInterface
    {
        $this->criteria->push($criteria);
    }

    /**
     * @return $this|CriteriaInterface
     */
    public function applyCriteria(): CriteriaInterface
    {
        if ($this->skipCriteria === true) {
            return $this;
        }
        foreach ($this->getCriteria() as $criteria) {
            if ($criteria instanceof Criteria) {
                $this->model = $criteria->apply($this->model, $this);
            }
        }

        return $this;
    }

    /**
     * @param array $relations
     *
     * @return array
     * @throws \ReflectionException
     */
    private function checkRelations(array $relations): array
    {
        if (empty($relations)) {
            return [];
        } elseif (trim(Arr::first($relations)) === '*' && count($relations) === 1) {
            return $this->getAllRelations();
        } else {
            return $relations;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery(): Builder
    {
        return $this->model->newQuery();
    }
}
