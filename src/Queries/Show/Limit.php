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
     * @const int
     */
    protected const MAX_LIMIT = 100;

    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        $max    = func_get_args();
        $count  = count($max);

        if ($count === 0) {
            return $this->builder
                ->limit(self::DEFAULT_LIMIT);
        }

        if ($count > 1 || is_int($max[0]) === false) {
            throw new InvalidArgumentException();
        }

        if ($max[0] === 0) {
            $max[0] = self::DEFAULT_LIMIT;
        }

        return $this->builder
            ->limit($max[0] > self::MAX_LIMIT ? self::MAX_LIMIT : $max[0]);
    }
}
