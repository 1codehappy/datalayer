<?php

namespace CodeHappy\DataLayer\Queries\Sorting;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderBy extends AbstractSorting
{
    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        $params = func_get_args();
        if (count($params) === 0) {
            throw new InvalidArgumentException();
        }
        $normalized = $this->normalize($params);
        $builder    = $this->builder;
        foreach ($normalized as $column => $orientation) {
            $builder = $builder->orderBy(DB::raw($column), $orientation);
        }
        return $builder;
    }
}
