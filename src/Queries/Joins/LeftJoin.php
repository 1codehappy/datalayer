<?php

namespace CodeHappy\DataLayer\Queries\Joins;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Queries\AbstractQuery;
use Closure;
use InvalidArgumentException;

class LeftJoin extends AbstractQuery
{
    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        $params     = func_get_args();
        $table      = array_shift($params);
        $count      = count($params);
        $relations  = $params;
        if (
            is_string($table) === false ||
            $count !== 1
        ) {
            throw new InvalidArgumentException();
        }

        if ($relations instanceof Closure === true) {
            return $this->builder
                ->leftJoin($table, $relations);
        }

        if (is_array($relations) === true) {
            $builder = $this->builder;
            $builder = $builder->leftJoin($table, function ($join) use ($relations) {
                foreach ($relations as $primary => $foreign) {
                    $join->on($primary, '=', $foreign);
                }
            });
            return $builder;
        }

        return $this->builder->leftJoin($table, DB::raw($relations));
    }
}
