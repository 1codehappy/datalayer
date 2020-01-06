<?php

namespace CodeHappy\DataLayer\Queries\Show;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Queries\AbstractQuery;
use InvalidArgumentException;

class Limit extends AbstractQuery
{
    /**
     * @const int
     */
    protected const DEFAULT_LIMIT = 50;

    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        $max    = func_get_args();
        $count  = count($max);
        $max = array_pop($max);

        if ($count === 0 || $max === 0) {
            return $this->builder
                ->limit(self::DEFAULT_LIMIT);
        }
        if ($count > 1 || is_int($max) === false) {
            throw new InvalidArgumentException();
        }

        return $this->builder
            ->limit($max);
    }
}
