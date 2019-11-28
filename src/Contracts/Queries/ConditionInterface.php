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
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function equals(string $column, $value, string $operator = 'AND'): ConditionInterface;

    /**
     * Isn't equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function not(string $column, $value, string $operator = 'AND'): ConditionInterface;

    /**
     * Like
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function like(string $column, $value, string $operator = 'AND'): ConditionInterface;

    /**
     * Not like
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function notLike(string $column, $value, string $operator = 'AND'): ConditionInterface;

    /**
     * Is greater Than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isGreaterThan(string $column, $value, string $operator = 'AND'): ConditionInterface;

    /**
     * Is greater than equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isGreaterThanEqualTo(string $column, $value, string $operator = 'AND'): ConditionInterface;

    /**
     * Is less than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isLessThan(string $column, $value, string $operator = 'AND'): ConditionInterface;

    /**
     * Is less than equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isLessThanEqualTo(string $column, $value, string $operator = 'AND'): ConditionInterface;

    /**
     * Is between value1 and value2
     *
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isBetween(): ConditionInterface;

    /**
     * Is null
     *
     * @param string $column
     * @param string $operator
     @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isNull(string $column, string $operator = 'AND'): ConditionInterface;

    /**
     * Is not null
     *
     * @param string $column
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isNotNull(string $column, string $operator = 'AND'): ConditionInterface;
}
