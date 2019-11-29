<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use CodeHappy\DataLayer\Facades\QueryFactory;

trait Showable
{
    /**
     * List Columns to show
     *
     * @return mixed
     */
    public function select()
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->select(...func_get_args());
        return $this;
    }

    /**
     * Define the max rows for query
     *
     * @param int $max
     * @return mixed
     */
    public function limit(int $max)
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->limit($max);
        return $this;
    }

    /**
     * Define the start position for the query
     *
     * @param int $startAt
     * @return mixed
     */
    public function offset(int $startAt)
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->offset($startAt);
        return $this;
    }
}
