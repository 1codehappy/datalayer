<?php

namespace CodeHappy\DataLayer;

use Illuminate\Container\Container as App;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use CodeHappy\DataLayer\Contracts\AggregationInterface;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Traits\Aggregable;
use CodeHappy\DataLayer\Traits\Aliases;
use CodeHappy\DataLayer\Traits\Massable;
use CodeHappy\DataLayer\Traits\Queryable;

abstract class Repository implements
    RepositoryInterface,
    AggregationInterface
{
    use Aggregable;
    use Aliases;
    use Massable;
    use Queryable;

    /**
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $model;

    /**
     * @var \Illuminate\Database\Eloquent\Builder|null
     */
    protected $builder;

    /**
     * @param \Illuminate\Container\Container $app
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function __construct(App $app, Model $model = null)
    {
        $this->model = $model ?? $app->make($this->model());
    }

    /**
     * Get the model class
     *
     * @abstract
     * @return string
     */
    abstract public function model(): string;

    /**
     * Get the builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder(): Builder
    {
        if ($this->builder === null) {
            $this->builder = $this->model->newQuery();
        }

        return $this->builder;
    }

    /**
     * New query
     *
     * @return $this
     */
    public function newQuery(): self
    {
        $this->builder = null;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function fetchById(int $resourceId)
    {
        return $this
            ->newQuery()
            ->builder()
            ->find($resourceId);
    }

    /**
     * Get all rows
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchAll(): Collection
    {
        return $this
            ->newQuery()
            ->fetch();
    }

    /**
     * {@inheritDoc}
     */
    public function fetch(): Collection
    {
        return $this->builder()->get();
    }

    /**
     * {@inheritDoc}
     */
    public function fetchFirst(): ?Model
    {
        return $this->builder()->first();
    }

    /**
     * {@inheritDoc}
     */
    public function paginate(int $perPage = 50): LengthAwarePaginator
    {
        return $this->builder()->paginate($perPage);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): ?Model
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function update(array $data, int $resourceId)
    {
        $model = $this->fetchById($resourceId);

        if ($model === null) {
            return null;
        }

        if ($model->fillable($data)->save() === false) {
            return false;
        }

        return $model;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($resourceIds): int
    {
        $ids = $resourceIds;
        if ($resourceIds instanceof Collection) {
            $ids = $resourceIds
                ->pluck($this->model->getKeyName())
                ->all();
        }
        if (is_int($resourceIds) === true) {
            $ids = [ $ids ];
        }
        $isDeleted = 0;
        foreach ($ids as $unique) {
            $model = $this->fetchById($unique);
            if ($model === null) {
                continue;
            }
            if ($model->delete() === false) {
                continue;
            }
            $isDeleted++;
        }
        return $isDeleted;
    }

    /**
     * {@inheritDoc}
     */
    public function distinct(): Collection
    {
        return $this->builder()->distinct();
    }

    /**
     * {@inheritDoc}
     */
    public function restoreFromTrash(): ?bool
    {
        return $this->builder()->restore();
    }
}
