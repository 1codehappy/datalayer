<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface ConditionInterface
{
    /**
     * Is equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function equals(string $column, $value, string $operator = 'AND');

    /**
     * Is equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isEqualTo(string $column, $value, string $operator = 'AND');

    /**
     * Isn't equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function not(string $column, $value, string $operator = 'AND');

    /**
     * Is equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isNotEqualTo(string $column, $value, string $operator = 'AND');

    /**
     * Is equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isDifferent(string $column, $value, string $operator = 'AND');

    /**
     * Like
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function like(string $column, $value, string $operator = 'AND');

    /**
     * Not like
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function notLike(string $column, $value, string $operator = 'AND');

    /**
     * Is greater Than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isGreaterThan(string $column, $value, string $operator = 'AND');

    /**
     * Is greater Than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function gt(string $column, $value, string $operator = 'AND');

    /**
     * Is greater than equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isGreaterThanEqualTo(string $column, $value, string $operator = 'AND');

    /**
     * Is greater than equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function gte(string $column, $value, string $operator = 'AND');

    /**
     * Is less than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isLessThan(string $column, $value, string $operator = 'AND');

    /**
     * Is less than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function lt(string $column, $value, string $operator = 'AND');

    /**
     * Is less than equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isLessThanEqualTo(string $column, $value, string $operator = 'AND');

    /**
     * Is less than equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function lte(string $column, $value, string $operator = 'AND');

    /**
     * Is between value1 and value2
     *
     * @return mixed
     */
    public function isBetween();

    /**
     * Is between value1 and value2
     *
     * @return mixed
     */
    public function between();

    /**
     * Is not between value1 and value2
     *
     * @return mixed
     */
    public function isNotBetween();

    /**
     * Is not between value1 and value2
     *
     * @return mixed
     */
    public function notBetween();

    /**
     * Is null
     *
     * @param string $column
     * @param string $operator
     @return mixed
     */
    public function isNull(string $column, string $operator = 'AND');

    /**
     * Is null
     *
     * @param string $column
     * @param string $operator
     @return mixed
     */
    public function null(string $column, string $operator = 'AND');

    /**
     * Is not null
     *
     * @param string $column
     * @param string $operator
     * @return mixed
     */
    public function isNotNull(string $column, string $operator = 'AND');

    /**
     * Is not null
     *
     * @param string $column
     * @param string $operator
     * @return mixed
     */
    public function notNull(string $column, string $operator = 'AND');
}
