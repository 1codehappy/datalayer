<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface SummaryInterface
{
    /**
     * Group by columns
     *
     * @return \CodeHappy\DataLayer\Contracts\Queries\SummaryInterface
     */
    public function groupBy(): SummaryInterface;

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
    ): SummaryInterface;
}
