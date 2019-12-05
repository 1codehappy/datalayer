<?php

namespace CodeHappy\DataLayer\Traits\Caching;

trait Aggregable
{
    /**
     * Count rows
     *
     * @return int
     */
    public function count(): int
    {
        return $this->cache()
            ->remember(
                $this->getCacheName(),
                $this->timeToLive(),
                function () {
                    return $this->repository->count();
                }
            );
    }

    /**
     * Sum rows by column
     *
     * @param string $column
     * @return mixed
     */
    public function sum(string $column)
    {
        return $this->cache()
            ->remember(
                $this->getCacheName(),
                $this->timeToLive(),
                function () use ($column) {
                    return $this->repository->sum($column);
                }
            );
    }

    /**
     * Get maximum value from column
     *
     * @param string $column
     * @return mixed
     */
    public function max(string $column)
    {
        return $this->cache()
            ->remember(
                $this->getCacheName(),
                $this->timeToLive(),
                function () use ($column) {
                    return $this->repository->max($column);
                }
            );
    }

    /**
     * Get minimum value from column
     *
     * @param string $column
     * @return mixed
     */
    public function min(string $column)
    {
        return $this->cache()
            ->remember(
                $this->getCacheName(),
                $this->timeToLive(),
                function () use ($column) {
                    return $this->repository->min($column);
                }
            );
    }

    /**
     * Get average value from column
     *
     * @param string $column
     * @return mixed
     */
    public function avg(string $column)
    {
        return $this->cache()
            ->remember(
                $this->getCacheName(),
                $this->timeToLive(),
                function () use ($column) {
                    return $this->repository->avg($column);
                }
            );
    }
}
