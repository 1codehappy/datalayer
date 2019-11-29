<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use CodeHappy\DataLayer\Facades\QueryFactory;
use CodeHappy\DataLayer\Traits\Queries\Aliases\Joinable as Aliases;
use Closure;

trait Joinable
{
    use Aliases;

    /**
     * Declare inner join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return mixed
     */
    public function innerJoin(string $table, $relations)
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
     * @return mixed
     */
    public function leftJoin(string $table, $relations)
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
     * @return mixed
     */
    public function rightJoin(string $table, $relations)
    {
        $this->builder = QueryFactoryQueryFactory::load($this->builder(), $this)
            ->rightJoin($table, $relations);
        return $this;
    }
}
