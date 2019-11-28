<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface EagerLoadingInterface
{
    /**
     * Eager loading to relations
     *
     * @param array|string $relations
     * @return $this
     */
    public function with($relations): self;
}
