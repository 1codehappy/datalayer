<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use CodeHappy\DataLayer\Contracts\Queries\SortInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;

trait Sortable
{
    /**
     * Sort query
     *
     * @return \CodeHappy\DataLayer\Contracts\Queries\SortInterface
     */
    public function orderBy(): SortInterface
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->orderBy(...func_get_args());
        return $this;
    }
}
