<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface JoinInterface
{
    /**
     * Declare inner join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return mixed
     */
    public function innerJoin(string $table, $relations);

    /**
     * Declare left join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return mixed
     */
    public function leftJoin(string $table, $relations);

    /**
     * Declare right join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return mixed
     */
    public function rightJoin(string $table, $relations);
}
