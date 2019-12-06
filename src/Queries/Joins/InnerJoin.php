<?php

namespace CodeHappy\DataLayer\Queries\Joins;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Queries\AbstractQuery;
use Closure;
use InvalidArgumentException;

class InnerJoin extends AbstractQuery
{
    /**
     * {@inheritDoc}
     */
    public function handle(): Builder
    {
        $params     = func_get_args();
        $table      = array_shift($params);
        $count      = count($params);
        if (
            is_string($table) === false ||
            $count !== 1
        ) {
            throw new InvalidArgumentException();
        }
        $relations = $params[0];

        if ($relations instanceof Closure === true) {
            return $this->builder
                ->join($table, $relations);
        }

        if (is_string($relations) === true) {
            $conditions = preg_split('/(\sAND\s|\sOR\s)/i', $relations);
            $relations  = [];
            foreach ($conditions as $condition) {
                preg_match(
                    '/(.*)(<>|=|>|<|>=|<=|!=|\sIS\s|IS\sNOT|\sLIKE\s|NOT\sLIKE)(.*)/i',
                    $condition,
                    $relation
                );
                array_shift($relation);
                $relations[] = $relation;
            }
        }

        if (is_array($relations) === true) {
            return $this->builder->join($table, function ($join) use ($relations) {
                foreach ($relations as $index => $relation) {
                    if (is_int($index) === false) {
                        $primary  = $index;
                        $operator = '=';
                        $foreign  = $relation;
                    }
                    if (
                        is_array($relation) === true &&
                        count($relation) === 3
                    ) {
                        list($primary, $operator, $foreign) = $relation;
                    }
                    $join->on(trim($primary), $operator, trim($foreign));
                }
            });
        }

        throw new InvalidArgumentException();
    }
}
