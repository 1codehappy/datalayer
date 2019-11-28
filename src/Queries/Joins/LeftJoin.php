<?php

namespace CodeHappy\DataLayer\Queries\Joins;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Queries\AbstractQuery;
use Closure;

class LeftJoin extends AbstractQuery
{
    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        list($table, $relations) = func_get_args();

        if ($relations instanceof Closure) {
            return $this->builder
                ->leftJoin($table, $relations);
        }
        $builder = $this->builder;
        $builder = $builder->leftJoin($table, function ($join) use ($relations) {
            foreach ($relations as $primary => $foreign) {
                $join->on($primary, '=', $foreign);
            }
        });
        return $builder;
    }
}
