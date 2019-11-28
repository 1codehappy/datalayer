<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use Illuminate\Support\Arr;
use CodeHappy\DataLayer\Contracts\Queries\ConditionInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;
use Closure;
use ReflectionClass;

trait Conditionable
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder|null
     */
    protected $clausules;

    /**
     * @var array
     */
    protected $methods;

    /**
     * Is equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function equals(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        if (is_array($value) === true) {
            $this->builder = QueryFactory::load($this->builder(), $this)
                ->whereIn($column, $value, $operator);
            return $this;
        }
        return $this->where($column, $value, '=', $operator);
    }

    /**
     * Isn't equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function not(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        if (is_array($value) === true) {
            $this->builder = QueryFactory::load($this->builder(), $this)
                ->whereNotIn($column, $value, $operator);
            return $this;
        }
        return $this->where($column, $value, '<>', $operator);
    }

    /**
     * Like
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function like(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->where($column, $value, 'LIKE', $operator);
    }

    /**
     * Not like
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function notLike(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->where($column, $value, 'NOT LIKE', $operator);
    }

    /**
     * Is greater Than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isGreaterThan(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->where($column, $value, '>', $operator);
    }

    /**
     * Is greater than equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isGreaterThanEqualTo(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->where($column, $value, '>=', $operator);
    }

    /**
     * Is less than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isLessThan(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->where($column, $value, '<', $operator);
    }

    /**
     * Is less than equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isLessThanEqualTo(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->where($column, $value, '<=', $operator);
    }

    /**
     * Is between value1 and value2
     *
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isBetween(): ConditionInterface
    {
        $builder = $this->clausules ?? $this->builder();
        $builder = QueryFactory::load($builder, $this)
            ->between(...func_get_args());
        $this->clausules ? $this->clausules = $builder : $this->builder = $builder;

        return $this;
    }

    /**
     * Is null
     *
     * @param string $column
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isNull(string $column, string $operator = 'AND'): ConditionInterface
    {
        $builder = $this->clausules ?? $this->builder();
        $builder = QueryFactory::load($builder, $this)
            ->null($column, $operator);
        $this->clausules ? $this->clausules = $builder : $this->builder = $builder;

        return $this;
    }

    /**
     * Is not null
     *
     * @param string $column
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isNotNull(string $column, string $operator = 'AND'): ConditionInterface
    {
        $builder = $this->clausules ?? $this->builder();
        $builder = QueryFactory::load($builder, $this)
            ->notNull($column, $operator);
        $this->clausules ? $this->clausules = $builder : $this->builder = $builder;

        return $this;
    }

    /**
     * Agroup clausules with 'AND'
     *
     * @param \Closure $clausules
     * @param \string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function agroup(Closure $clausules, string $operator = 'AND'): ConditionInterface
    {
        $this->clausules = $this->model->newModelQuery();
        call_user_func($closure, $this);
        $this->getQuery()->addNestedWhereQuery($this->clausules->getQuery(), $operator);
        $this->clausules = null;
        return $this;
    }

    /**
     * Alias to equals()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isEqualTo(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->equals($column, $value, $operator);
    }

    /**
     * Alias to not()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function isNotEqualTo(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->not($column, $value, $operator);
    }

    /**
     * Alias to isGreaterThan()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function after(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->isGreaterThan($column, $value, $operator);
    }

    /**
     * Alias to isGreaterThanEqualTo()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function since(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->isGreaterThanEqualTo($column, $value, $operator);
    }

    /**
     * Alias to isLessThan()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function before(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->isLessThan($column, $value, $operator);
    }

    /**
     * Alias to isLessThanEqualTo()
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function until(string $column, $value, string $operator = 'AND'): ConditionInterface
    {
        return $this->isLessThanEqualTo($column, $value, $operator);
    }

    /**
     * Alias to isBetween()
     *
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function among(): ConditionInterface
    {
        return $this->isBetween(...func_get_args());
    }

    /**
     * Apply where clausule
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @param string $operator
     * @return \CodeHappy\DataLayer\Contracts\Queries\ConditionInterface
     */
    public function where(
        string $column,
        $value,
        string $comparator = '=',
        string $operator = 'AND'
    ): ConditionInterface {
        $builder = $this->clausules ?? $this->builder();
        $builder = QueryFactory::load($builder, $this)
            ->where($column, $comparator, $value, $operator);
        $this->clausules ? $this->clausules = $builder : $this->builder = $builder;

        return $this;
    }

    /**
     * Get conditions methods adding prefix 'or'
     *
     * @return array
     */
    public function methodsWithOr(): array
    {
        if ($this->methods === null) {
            $interface     = new ReflectionClass(ConditionInterface::class);
            $this->methods = Arr::pluck($interface->getMethods(), 'name');
            $this->methods = array_map(function ($method) {
                return 'or' . ucfirst($method);
            }, $this->methods);
        }
        return $this->methods;
    }

    /**
     * Adding 'OR' clausules to methods
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (
            in_array($name, $this->methodsWithOr()) === true &&
            (
                is_array(end($arguments)) === true ||
                in_array(strtoupper(end($arguments)), ['AND', 'OR']) === false
            )
        ) {
            $name = lcfirst(substr($name, 2));
            $arguments[] = 'OR';
        }
        return $this->{$name}(...$arguments);
    }
}
