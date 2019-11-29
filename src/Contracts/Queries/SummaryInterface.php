<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface SummaryInterface
{
    /**
     * Group by columns
     *
     * @return mixed
     */
    public function groupBy();

    /**
     * Having
     *
     * @param string $column
     * @param string|null $comparator
     * @param string|null $value
     * @param string $operator
     * @return mixed
     */
    public function having(
        string $column,
        ?string $comparator = null,
        ?string $value = null,
        string $operator = 'AND'
    );
}
