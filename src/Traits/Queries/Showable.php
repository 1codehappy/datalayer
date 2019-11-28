<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use CodeHappy\DataLayer\Contracts\Queries\ShowInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;

trait Showable
{
    /**
     * List Columns to show
     *
     * @return \CodeHappy\DataLayer\Contracts\Queries\ShowInterface
     */
    public function select(): ShowInterface
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->select(...func_get_args());
        return $this;
    }

    /**
     * Define the max rows for query
     *
     * @param int $max
     * @return \CodeHappy\DataLayer\Contracts\Queries\ShowInterface
     */
    public function limit(int $max): ShowInterface
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->limit($max);
        return $this;
    }

    /**
     * Define the start position for the query
     *
     * @param int $startAt
     * @return \CodeHappy\DataLayer\Contracts\Queries\ShowInterface
     */
    public function offset(int $startAt): ShowInterface
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->offset($startAt);
        return $this;
    }
}
