<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface ShowInterface
{
    /**
     * List Columns to show
     *
     * @return mixed
     */
    public function select();

    /**
     * Define the max rows for query
     *
     * @param int $max
     * @return mixed
     */
    public function limit(int $max);

    /**
     * Define the start position for the query
     *
     * @param int $startAt
     * @return mixed
     */
    public function offset(int $startAt);
}
