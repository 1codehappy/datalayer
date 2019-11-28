<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface SortInterface
{
    /**
     * Sort query
     *
     * @return \CodeHappy\DataLayer\Contracts\Queries\SortInterface
     */
    public function orderBy(): SortInterface;
}
