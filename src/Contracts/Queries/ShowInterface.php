<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface ShowInterface
{
    /**
     * List Columns to show
     *
     * @return \CodeHappy\DataLayer\Contracts\Queries\ShowInterface
     */
    public function select(): ShowInterface;

    /**
     * Define the max rows for query
     *
     * @param int $max
     * @return \CodeHappy\DataLayer\Contracts\Queries\ShowInterface
     */
    public function limit(int $max): ShowInterface;

    /**
     * Define the start position for the query
     *
     * @param int $startAt
     * @return \CodeHappy\DataLayer\Contracts\Queries\ShowInterface
     */
    public function offset(int $startAt): ShowInterface;
}
