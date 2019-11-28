<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use CodeHappy\DataLayer\Contracts\Queries\EagerLoadingInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;

trait EagerLoading
{
    /**
     * Eager loading to relations
     *
     * @param array|string $relations
     * @return \CodeHappy\DataLayer\Contracts\Queries\EagerLoadingInterface
     */
    public function with($relations): EagerLoadingInterface
    {
        if (is_string($relations) === true) {
            $relations = explode(',', $relations);
        }

        $this->builder = QueryFactory::load($this->builder(), $this)
            ->with($relations);

        return $this;
    }
}
