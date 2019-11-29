<?php

namespace CodeHappy\DataLayer\Traits\Queries\Aliases;

trait Joinable
{
    /**
     * Alias to innerJoin() method
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return mixed
     */
    public function join(string $table, $relations)
    {
        return $this->innerJoin($table, $relations);
    }

}
