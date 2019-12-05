<?php

namespace CodeHappy\DataLayer\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    /**
     * Find by ID
     *
     * @param int $resourceId
     * @return mixed
     */
    public function fetchById(int $resourceId);

    /**
     * Get all
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetch(): Collection;

    /**
     * Find first
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function fetchFirst(): ?Model;

    /**
     * Paginate data
     *
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $limit = 50): LengthAwarePaginator;

    /**
     * New entry
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function create(array $data): ?Model;

    /**
     * Update data
     *
     * @param array $data
     * @param int $resourceId
     * @return \Illuminate\Database\Eloquent\Model|null|bool
     */
    public function update(array $data, int $resourceId);

    /**
     * Remove entry(ies)
     *
     * @param \Illuminate\Support\Collection|array|int $resourceIds
     * @return int
     */
    public function delete($resourceIds): int;

    /**
     * Get distinct rows
     *
     * @return \Illuminate\Support\Collection
     */
    public function distinct(): Collection;
}
