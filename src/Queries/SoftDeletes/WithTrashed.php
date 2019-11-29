<?php

namespace CodeHappy\DataLayer\Queries\SoftDeletes;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Queries\AbstractQuery;

class WithTrashed extends AbstractQuery
{
    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        return $this->builder->withTrashed();
    }
}
