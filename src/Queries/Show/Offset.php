<?php

namespace CodeHappy\DataLayer\Queries\Show;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Queries\AbstractQuery;
use InvalidArgumentException;

class Offset extends AbstractQuery
{
    /**
     * @const int
     */
    protected const DEFAULT_OFFSET = 0;

    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        $startsAt = func_get_args();
        $count    = count($startsAt);
        $startsAt = array_pop($startsAt);

        if ($count === 0) {
            return $this->builder
                ->offset(self::DEFAULT_OFFSET);
        }

        if ($count > 1 || is_int($startsAt) === false) {
            throw new InvalidArgumentException();
        }

        return $this->builder
            ->offset($startsAt);
    }
}
