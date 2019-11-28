<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use CodeHappy\DataLayer\Contracts\Queries\SummaryInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;

trait Summariable
{
    /**
     * Group by columns
     *
     * @return \CodeHappy\DataLayer\Contracts\Queries\SummaryInterface
     */
    public function groupBy(): SummaryInterface
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->groupBy(...func_get_args());
        return $this;
    }

    /**
     * Having
     *
     * @param string $column
     * @param string|null $comparator
     * @param string|null $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\SummaryInterface
     */
    public function having(
        string $column,
        ?string $comparator = null,
        ?string $value = null,
        string $operator = 'AND'
    ): SummaryInterface {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->having($column, $comparator, $value, $operator);
        return $this;
    }
}
