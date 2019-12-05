<?php

namespace CodeHappy\DataLayer\Traits\Queries;

use Illuminate\Support\Arr;
use CodeHappy\DataLayer\Contracts\Queries\ConditionInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;
use CodeHappy\DataLayer\Traits\Queries\Aliases\Conditionable as Aliases;
use BadMethodCallException;
use Closure;
use ReflectionClass;

trait Conditionable
{
    use Aliases;

    /**
     * @var \Closure
     */
    protected $clausules;

    /**
     * @var array
     */
    protected $methods;

    /**
     * Where
     *
     * @param \Closure|string $param
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function where($param)
    {
        if ($param instanceof Closure) {
            return $this->groupConditions($param);
        }

        $this->builder = QueryFactory::load($this->builder(), $this)
            ->where($param);
        return $this;
    }

    /**
     * Is equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function equals(string $column, $value, string $operator = 'AND')
    {
        if (is_array($value) === true) {
            $this->builder = QueryFactory::load($this->builder(), $this)
                ->whereIn($column, $value, $operator);
            return $this;
        }
        return $this->applyCondition($column, $value, '=', $operator);
    }

    /**
     * Isn't equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function not(string $column, $value, string $operator = 'AND')
    {
        if (is_array($value) === true) {
            $this->builder = QueryFactory::load($this->builder(), $this)
                ->whereNotIn($column, $value, $operator);
            return $this;
        }
        return $this->applyCondition($column, $value, '<>', $operator);
    }

    /**
     * Like
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function like(string $column, $value, string $operator = 'AND')
    {
        return $this->applyCondition($column, $value, 'LIKE', $operator);
    }

    /**
     * Not like
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function notLike(string $column, $value, string $operator = 'AND')
    {
        return $this->applyCondition($column, $value, 'NOT LIKE', $operator);
    }

    /**
     * Is greater Than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isGreaterThan(string $column, $value, string $operator = 'AND')
    {
        return $this->applyCondition($column, $value, '>', $operator);
    }

    /**
     * Is greater than equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isGreaterThanEqualTo(string $column, $value, string $operator = 'AND')
    {
        return $this->applyCondition($column, $value, '>=', $operator);
    }

    /**
     * Is less than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isLessThan(string $column, $value, string $operator = 'AND')
    {
        return $this->applyCondition($column, $value, '<', $operator);
    }

    /**
     * Is less than equal to
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return mixed
     */
    public function isLessThanEqualTo(string $column, $value, string $operator = 'AND')
    {
        return $this->applyCondition($column, $value, '<=', $operator);
    }

    /**
     * Is between value1 and value2
     *
     * @return mixed
     */
    public function isBetween()
    {
        $builder = $this->clausules ?? $this->builder();
        $builder = QueryFactory::load($builder, $this)
            ->between(...func_get_args());
        $this->clausules ? $this->clausules = $builder : $this->builder = $builder;

        return $this;
    }

    /**
     * Is Not between value1 and value2
     *
     * @return mixed
     */
    public function isNotBetween()
    {
        $builder = $this->clausules ?? $this->builder();
        $builder = QueryFactory::load($builder, $this)
            ->notBetween(...func_get_args());
        $this->clausules ? $this->clausules = $builder : $this->builder = $builder;

        return $this;
    }

    /**
     * Is null
     *
     * @param string $column
     * @param string $operator
     * @return mixed
     */
    public function isNull(string $column, string $operator = 'AND')
    {
        $builder = $this->clausules ?? $this->builder();
        $builder = QueryFactory::load($builder, $this)
            ->isNull($column, $operator);
        $this->clausules ? $this->clausules = $builder : $this->builder = $builder;

        return $this;
    }

    /**
     * Is not null
     *
     * @param string $column
     * @param string $operator
     * @return mixed
     */
    public function isNotNull(string $column, string $operator = 'AND')
    {
        $builder = $this->clausules ?? $this->builder();
        $builder = QueryFactory::load($builder, $this)
            ->isNotNull($column, $operator);
        $this->clausules ? $this->clausules = $builder : $this->builder = $builder;

        return $this;
    }

    /**
     * Agroup clausules with 'AND'
     *
     * @param \Closure $clausules
     * @param \string $operator
     * @return mixed
     */
    public function groupConditions(Closure $clausules, string $operator = 'AND')
    {
        $this->clausules = $this->model->newModelQuery();
        call_user_func($clausules, $this);
        $this->getQuery()->addNestedWhereQuery($this->clausules->getQuery(), $operator);
        $this->clausules = null;
        return $this;
    }

    /**
     * Apply where clausule
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @param string $operator
     * @return mixed
     */
    public function applyCondition(
        string $column,
        $value,
        string $comparator = '=',
        string $operator = 'AND'
    ) {
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

        if (method_exists($this, $name) === false) {
            $class = get_called_class();
            throw new BadMethodCallException("Call to undefined method {$class}::{$name}()");
        }

        return $this->{$name}(...$arguments);
    }
}
