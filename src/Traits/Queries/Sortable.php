<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use CodeHappy\DataLayer\Facades\QueryFactory;

trait Sortable
{
    /**
     * Sort query
     *
     * @return mixed
     */
    public function orderBy()
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->orderBy(...func_get_args());
        return $this;
    }
}
