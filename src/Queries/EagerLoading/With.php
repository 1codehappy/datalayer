<?php

namespace CodeHappy\DataLayer\Queries\EagerLoading;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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
        $args   = func_get_args();
        $count  = count($args);
        if ($count === 0) {
            throw new InvalidArgumentException();
        }
        if ($count === 1) {
            $args = is_array($args[0]) === true ? $args[0] : explode(',', $args[0]);
        }
        $relations = [];
        foreach ($args as $relation) {
            $relations[] = DB::raw(trim($relation));
        }
        return $this->builder->with($relations);
    }
}
