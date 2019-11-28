<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use CodeHappy\DataLayer\Contracts\Queries\JoinInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;
use Closure;

trait Joinable
{
    /**
     * Declare inner join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return \CodeHappy\DataLayer\Contracts\Queries\JoinInterface
     */
    public function innerJoin(string $table, $relations): JoinInterface
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->innerJoin($table, $relations);
        return $this;
    }

    /**
     * Declare left join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return \CodeHappy\DataLayer\Contracts\Queries\JoinInterface
     */
    public function leftJoin(string $table, $relations): JoinInterface
    {
        $this->builder = QueryFactory::load($this->builder(), $this)
            ->leftJoin($table, $relations);
        return $this;
    }

    /**
     * Declare right join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return \CodeHappy\DataLayer\Contracts\Queries\JoinInterface
     */
    public function rightJoin(string $table, $relations): JoinInterface
    {
        $this->builder = QueryFactoryQueryFactory::load($this->builder(), $this)
            ->rightJoin($table, $relations);
        return $this;
    }

    /**
     * Alias to innerJoin() method
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return \CodeHappy\DataLayer\Contracts\Queries\JoinInterface
     */
    public function join(string $table, $relations): JoinInterface
    {
        return $this->innerJoin($table, $relations);
    }
}
