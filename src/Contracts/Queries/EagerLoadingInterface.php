<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface EagerLoadingInterface
{
    /**
     * Eager loading to relations
     *
     * @param array|string $relations
     * @return mixed
     */
    public function with($relations);
}
