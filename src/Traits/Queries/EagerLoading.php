<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use CodeHappy\DataLayer\Facades\QueryFactory;

trait EagerLoading
{
    /**
     * Eager loading to relations
     *
     * @param array|string $relations
     * @return mixed
     */
    public function with($relations)
    {
        if (is_string($relations) === true) {
            $relations = explode(',', $relations);
        }

        $this->builder = QueryFactory::load($this->builder(), $this)
            ->with($relations);

        return $this;
    }
}
