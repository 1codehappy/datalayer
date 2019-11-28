<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface JoinInterface
{
    /**
     * Declare inner join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return \CodeHappy\DataLayer\Contracts\Queries\JoinInterface
     */
    public function innerJoin(string $table, $relations): JoinInterface;

    /**
     * Declare left join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return \CodeHappy\DataLayer\Contracts\Queries\JoinInterface
     */
    public function leftJoin(string $table, $relations): JoinInterface;

    /**
     * Declare right join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return \CodeHappy\DataLayer\Contracts\Queries\JoinInterface
     */
    public function rightJoin(string $table, $relations): JoinInterface;
}
