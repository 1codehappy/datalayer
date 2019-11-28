<?php

namespace CodeHappy\DataLayer\Queries\Joins;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Queries\AbstractQuery;
use Closure;

class RightJoin extends AbstractQuery
{
    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        list($table, $relations) = func_get_args();

        if ($relations instanceof Closure) {
            return $this->builder
                ->rightJoin($table, $relations);
        }
        $builder = $this->builder;
        $builder = $builder->rightJoin($table, function ($join) use ($relations) {
            foreach ($relations as $primary => $foreign) {
                $join->on($primary, '=', $foreign);
            }
        });
        return $builder;
    }
}
