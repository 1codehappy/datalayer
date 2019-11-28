<?php

namespace CodeHappy\DataLayer\Queries\Conditions;

use CodeHappy\DataLayer\Queries\AbstractQuery;

abstract class AbstractCondition extends AbstractQuery
{
    /**
     * @const string
     */
    protected const OPERATORS = [
        'AND',
        'OR',
    ];

    /**
     * @const string
     */
    protected const DEFAULT_OPERATOR = 'AND';

    /**
     * Get the last param
     *
     * @param array $params
     * @return string
     */
    public function lastParam(array &$params): string
    {
        $lastParam  = end($params);

        if (
            is_array($lastParam) === false &&
            in_array(strtoupper($lastParam), self::OPERATORS) === true
        ) {
            return array_pop($params);
        }
        return self::DEFAULT_OPERATOR;
    }
}
