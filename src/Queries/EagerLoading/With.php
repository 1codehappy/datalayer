<?php

namespace CodeHappy\DataLayer\Queries\EagerLoading;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Queries\AbstractQuery;
use InvalidArgumentException;

class With extends AbstractQuery
{
    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     */
    public function handle(): Builder
    {
        $params     = func_get_args();
        $count      = count($params);
        if ($count === 0) {
            throw new InvalidArgumentException();
        }
        $relations = $params;
        if (is_array($params[0]) === true) {
            $relations = $params[0];
        }
        return $this->builder->with($relations);
    }
}
