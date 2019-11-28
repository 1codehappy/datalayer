<?php

namespace CodeHappy\DataLayer\Queries\Conditions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use InvalidArgumentException;

class IsNull extends AbstractCondition
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
        return $this->builder->whereNull(DB::raw($params[0]), $operator);
    }
}
