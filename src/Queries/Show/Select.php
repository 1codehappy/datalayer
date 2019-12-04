<?php

namespace CodeHappy\DataLayer\Queries\Show;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Queries\AbstractQuery;
use InvalidArgumentException;

class Select extends AbstractQuery
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
        if ($count === 1) {
            $args = is_array($args[0]) === true ? $args[0] : explode(',', $args[0]);
        }
        $columns = [];
        foreach ($args as $column) {
           $columns[] = DB::raw(trim($column));
        }
        return $this->builder->select($columns);
    }
}
