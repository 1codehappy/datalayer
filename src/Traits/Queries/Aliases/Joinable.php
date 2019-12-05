<?php

namespace CodeHappy\DataLayer\Traits\Queries\Aliases;

trait Joinable
{
    /**
     * Alias to innerJoin() method
     *
     * @param string $table
     * @param \Closure|array|string $relations
     * @return mixed
     */
    public function join(string $table, $relations)
    {
        return $this->innerJoin($table, $relations);
    }

    /**
     * Alias to leftJoin() method
     *
     * @param string $table
     * @param \Closure|array|string $relations
     * @return mixed
     */
    public function ljoin(string $table, $relations)
    {
        return $this->leftJoin($table, $relations);
    }

    /**
     * Alias to rightJoin() method
     *
     * @param string $table
     * @param \Closure|array|string $relations
     * @return mixed
     */
    public function rjoin(string $table, $relations)
    {
        return $this->rightJoin($table, $relations);
    }
}
