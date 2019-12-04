<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use CodeHappy\DataLayer\Facades\QueryFactory;

trait EagerLoading
{
    /**
     * Eager loading to relations
     *
     * @return mixed
     */
    public function with()
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->with(...func_get_args());

        return $this;
    }
}
