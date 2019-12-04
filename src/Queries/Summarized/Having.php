<?php

namespace CodeHappy\DataLayer\Queries\Summarized;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Queries\AbstractQuery;
use InvalidArgumentException;

class Having extends AbstractQuery
{
    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        $params = func_get_args();
        $count = count($params);
        if ($count !== 1 && $count !== 3) {
            throw new InvalidArgumentException();
        }
        $params[0] = DB::raw($params[0]);
        return $count === 1 ?
            $this->builder->havingRaw($params[0]) :
            $this->builder->having(...$params);
    }
}
