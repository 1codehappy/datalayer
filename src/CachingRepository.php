<?php

namespace CodeHappy\DataLayer;

use Illuminate\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use CodeHappy\DataLayer\Contracts\AggregationInterface;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Traits\Caching\Aggregable;
use CodeHappy\DataLayer\Traits\Caching\Massable;
use CodeHappy\DataLayer\Traits\Debugable;
use CodeHappy\DataLayer\Traits\Queryable;
use BadMethodCallException;

abstract class CachingRepository implements
    RepositoryInterface,
    AggregationInterface
{
    use Aggregable;
    use Debugable;
    use Massable;
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
     * Get builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder(): Builder
    {
        return $this->repository->builder();
    }

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
     * @return string
     */
    public function getCacheName(): string
    {
        $database = $this->repository
            ->builder()
            ->getConnection()
            ->getDatabaseName();

        $sql = $this->toSql();

        return  md5($database . '|' . $sql);
    }

    /**
     * {@inheritDoc}
     */
    public function fetchById(int $resourceId)
    {
        return $this->cache()
            ->remember(
                $this->getCacheName(),
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
                $this->getCacheName(),
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
                $this->getCacheName(),
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
                $this->getCacheName(),
                $this->timeToLive(),
                function () {
                    return $this->repository->fetchFirst();
                }
            );
    }

    /**
     * {@inheritDoc}
     */
    public function paginate(int $perPage = 50): LengthAwarePaginator
    {
        return $this->cache()
            ->remember(
                $this->getCacheName(),
                $this->timeToLive(),
                function () use ($perPage) {
                    return $this->repository->paginate($perPage);
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
            $this->clearCache();
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
            $this->clearCache();
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
            $this->clearCache();
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
                $this->getCacheName(),
                $this->timeToLive(),
                function () {
                    return $this->repository->distinct();
                }
            );
    }

    /**
     * Clear cache
     *
     * @return bool
     */
    public function clearCache(): bool
    {
        return $this->cache()->clear();
    }
}
