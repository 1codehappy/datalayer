<?php

namespace CodeHappy\DataLayer\Traits\Queries\Aliases;

use Closure;

trait Conditionable
{
    /**
     * Alias to equals()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isEqualTo(string $column, $value, string $operator = 'AND')
    {
        return $this->equals($column, $value, $operator);
    }

    /**
     * Alias to not()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isNotEqualTo(string $column, $value, string $operator = 'AND')
    {
        return $this->not($column, $value, $operator);
    }

    /**
     * Alias to not()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isDifferent(string $column, $value, string $operator = 'AND')
    {
        return $this->not($column, $value, $operator);
    }

    /**
     * Alias to isBetween()
     *
     * @return mixed
     */
    public function between()
    {
        return $this->isBetween(...func_get_args());
    }

    /**
     * Alias to isBetween()
     *
     * @return mixed
     */
    public function notBetween()
    {
        return $this->isNotBetween(...func_get_args());
    }

    /**
     * Alias to isGreaterThan()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function gt(string $column, $value, string $operator = 'AND')
    {
        return $this->isGreaterThan($column, $value, $operator);
    }

    /**
     * Alias to isGreaterThanEqualTo()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function gte(string $column, $value, string $operator = 'AND')
    {
        return $this->isGreaterThanEqualTo($column, $value, $operator);
    }

    /**
     * Alias to isLessThan()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function lt(string $column, $value, string $operator = 'AND')
    {
        return $this->isLessThan($column, $value, $operator);
    }

    /**
     * Alias to isLessThanEqualTo()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function lte(string $column, $value, string $operator = 'AND')
    {
        return $this->isLessThanEqualTo($column, $value, $operator);
    }

    /**
     * Alias to isNull()
     *
     * @param string $column
     * @param string $operator
     * @return mixed
     */
    public function null(string $column, string $operator = 'AND')
    {
        return $this->isNull($column, $operator);
    }

    /**
     * Alias to isNotNull()
     *
     * @param string $column
     * @param string $operator
     * @return mixed
     */
    public function notNull(string $column, string $operator = 'AND')
    {
        return $this->isNotNull($column, $operator);
    }

    /**
     * Alias to groupConditions()
     *
     * @param Closure $closure
     * @return mixed
     */
    public function and(Closure $closure)
    {
        return $this->groupConditions($closure, 'AND');
    }

    /**
     * Alias to groupConditions()
     *
     * @param Closure $closure
     * @return mixed
     */
    public function or(Closure $closure)
    {
        return $this->groupConditions($closure, 'or');
    }
}
