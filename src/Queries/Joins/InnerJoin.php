<?php

namespace CodeHappy\DataLayer\Queries\Joins;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Queries\AbstractQuery;
use Closure;
use InvalidArgumentException;

class InnerJoin extends AbstractQuery
{
    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        $params = func_get_args();
        $table  = array_shift($params);
        if (is_string($table) === false) {
            throw new InvalidArgumentException();
        }
        $count     = count($params);
        $relations = $params;
        if ($count === 1 && $relations instanceof Closure) {
            return $this->builder
                ->join($table, $relations);
        }

        if ($count === 2 && is_string($params[0]) === true && is_string($params[1]) === true) {
            $relations = [
                $params[0] => $params[1],
            ];
        }

        if (is_array($relations) === true) {
            $filtered = array_filter($relations, function ($value, $key) {
                return is_string($value) === true && is_string($key) === true;
            });

            if (count($filtered) !== count($relations)) {
                throw new InvalidArgumentException();
            }

            $builder = $this->builder;
            $builder = $builder->join($table, function ($join) use ($relations) {
                foreach ($relations as $primary => $foreign) {
                    $join->on($primary, '=', $foreign);
                }
            });
            return $builder;
        }
        throw new InvalidArgumentException();
    }
}
