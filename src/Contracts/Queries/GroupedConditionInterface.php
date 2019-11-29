<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

use Closure;

interface GroupedConditionInterface
{
    /**
     * Where raw sql
     *
     * @param \Closure|string $sql
     * @param string $operator
     * @return mixed
     */
    public function where($sql, string $operator = 'AND');
}
