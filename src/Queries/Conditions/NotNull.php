<?php

namespace CodeHappy\DataLayer\Queries\Conditions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class NotNull extends AbstractCondition
{
    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     */
    public function handle(): Builder
    {
        $params     = func_get_args();
        $operator   = $this->lastParam($params);
        if (count($params) !== 1) {
            throw new InvalidArgumentException();
        }
        return $this->builder->whereNotNull(DB::raw($params[0]), $operator);
    }
}
