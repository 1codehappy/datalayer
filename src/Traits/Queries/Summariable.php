<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use CodeHappy\DataLayer\Facades\QueryFactory;

trait Summariable
{
    /**
     * Group by columns
     *
     * @return mixed
     */
    public function groupBy()
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->groupBy(...func_get_args());
        return $this;
    }

    /**
     * Having
     *
     * @return mixed
     */
    public function having()
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->having(...func_get_args());
        return $this;
    }
}
