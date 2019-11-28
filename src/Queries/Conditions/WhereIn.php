<?php

namespace CodeHappy\DataLayer\Queries\Conditions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WhereIn extends AbstractCondition
{
    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     */
    public function handle(): Builder
    {
        $params     = func_get_args();
        $operator   = $this->lastParam($params);
        $count      = count($params);
        $last       = end($params);
        if ($count <= 1) {
            throw new InvalidArgumentException();
        }
        $column = array_shift($params);
        $values = is_array($last) === true ? $last : $params;
        return $this->builder->whereIn(DB::raw($column), $values, $operator);
    }
}
