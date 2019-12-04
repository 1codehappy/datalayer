<?php

namespace CodeHappy\DataLayer;

use Illuminate\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use CodeHappy\DataLayer\Contracts\AggregationInterface;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Traits\Caching\Aggregable;
use CodeHappy\DataLayer\Traits\Queryable;
use BadMethodRequestException;

abstract class CachingRepository implements
    RepositoryInterface,
    AggregationInterface
{
    use Aggregable;
    use Queryable;

    /**
     * @var \CodeHappy\DataLayer\Repository
     */
    protected $repository;

    /**
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * @var string|array
     */
    protected $tags;

    /**
     * @param \CodeHappy\DataLayer\Repository $repository
     * @param \Illuminate\Cache\Repository $cache
     * @return void
     */
    public function __construct(Repository $repository, Cache $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * Get the time-to-live in seconds
     *
     * @abstract
     * @return int
     */
    abstract public function timeToLive(): int;

    /**
     * Get cache repository
     *
     * @return \Illuminate\Cache\Repository
     */
    public function cache(): Cache
    {
        if ($this->tags !== null) {
            return $this->cache->tags($this->tags);
        }

        return $this->cache;
    }

    /**
     * Get cache name
     *
     * @param \CodeHappy\DataLayer\Repository $repository
     * @return string
     */
    public function getCacheName(Repository $repository): string
    {
        $database = $repository
            ->builder()
            ->getConnection()
            ->getDatabaseName();

        $rawSql = $repository->builder()->toRawSql();

        return  md5($database . '|' . $rawSql);
    }

    /**
     * {@inheritDoc}
     */
    public function fetchById(int $resourceId): ?Model
    {
        return $this->cache()
            ->remember(
                $this->getCacheName($this->repository),
                $this->timeToLive(),
                function () use ($resourceId) {
                    return $this->repository->fetchById($resourceId);
                }
            );
    }

    /**
     * Get all rows
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchAll(): Collection
    {
        return $this->cache()
            ->remember(
                $this->getCacheName($this->repository),
                $this->timeToLive(),
                function () {
                    return $this->repository->fetchAll();
                }
            );
    }

    /**
     * {@inheritDoc}
     */
    public function fetch(): Collection
    {
        return $this->cache()
            ->remember(
                $this->getCacheName($this->repository),
                $this->timeToLive(),
                function () {
                    return $this->repository->fetch();
                }
            );
    }

    /**
     * {@inheritDoc}
     */
    public function fetchFirst(): ?Model
    {
        return $this->cache()
            ->remember(
                $this->getCacheName($this->repository),
                $this->timeToLive(),
                function () {
                    return $this->repository->fetchFirst();
                }
            );
    }

    /**
     * {@inheritDoc}
     */
    public function paginate(int $limit = 50): LengthAwarePaginator
    {
        return $this->cache()
            ->remember(
                $this->getCacheName($this->repository),
                $this->timeToLive(),
                function () use ($limit) {
                    return $this->repository->paginate($limit);
                }
            );
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): ?Model
    {
        $model = $this->repository->create($data);
        if ($model instanceof Model) {
            $this->cache()->clear();
        }
        return $model;
    }

    /**
     * {@inheritDoc}
     */
    public function update(array $data, int $resourceId)
    {
        $model = $this->repository->update($data, $resourceId);
        if ($model instanceof Model) {
            $this->cache()->clear();
        }
        return $model;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($resourceIds): int
    {
        $count = $this->repository->delete($resourceIds);
        if ($count > 0) {
            $this->cache()->clear();
        }
        return $count;
    }

    /**
     * {@inheritDoc}
     */
    public function distinct(): Collection
    {
        return $this->cache()
            ->remember(
                $this->getCacheName($this->repository),
                $this->timeToLive(),
                function () {
                    return $this->repository->distinct();
                }
            );
    }

    /**
     * {@inheritDoc}
     */
    public function restoreFromTrash(): ?bool
    {
        if (method_exists($this->repository, 'restoreFromTrash') === false) {
            $class = get_class($this->repository);
            throw new BadMethodCallException("Call to undefined method {$class}::restoreFromTrash()");
        }

        $isRestored = $this->repository
            ->restoreFromTrash();
        if ($isRestored === true) {
            $this->cache()
                ->clear();
        }
        return $isRestored;
    }
}
