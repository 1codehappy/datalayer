<?php

namespace CodeHappy\DataLayer\Queries\Summarized;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Queries\AbstractQuery;
use InvalidArgumentException;

class GroupBy extends AbstractQuery
{
    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        $args   = func_get_args();
        $count  = count($args);
        if ($count === 0) {
            throw new InvalidArgumentException();
        }
        if ($count === 1 && is_array($args[0])) {
            $args = $args[0];
        }
        $columns = [];
        foreach ($args as $column) {
            $columns[] = DB::raw($column);
        }
        return $this->builder->groupBy($columns);
    }
}
