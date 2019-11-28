<?php

namespace CodeHappy\DataLayer\Queries\Conditions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class Between extends AbstractCondition
{
    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     */
    public function handle(): Builder
    {
        $params     = func_get_args();
        $lastParam  = $this->lastParam($params);
        $count      = count($params);
        if (
            (
                $count !== 2 &&
                $count !== 3
            ) ||
            is_array($params[0]) === true
        ) {
            throw new InvalidArgumentException();
        }
        if (
            $count === 2 &&
            is_array($params[1]) === true &&
            count($params[1]) === 2 &&
            is_array($params[1][0]) === false &&
            is_array($params[1][1]) === false
        ) {
            return $this->builder->between(DB::raw($params[0]), $params[1], $lastParam);
        }
        if (
            $count === 3 &&
            is_array($params[1]) === false &&
            is_array($params[2]) === false
        ) {
            return $this->builder->between(DB::raw($params[0]), [
                $params[1],
                $params[2],
            ], $lastParam);
        }

        throw new InvalidArgumentException();
    }
}
