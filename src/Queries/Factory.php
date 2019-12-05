<?php

namespace CodeHappy\DataLayer\Queries;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Conditions\Where;
use CodeHappy\DataLayer\Queries\Conditions\WhereIn;
use CodeHappy\DataLayer\Queries\Conditions\WhereNotIn;
use CodeHappy\DataLayer\Queries\Conditions\Between;
use CodeHappy\DataLayer\Queries\Conditions\NotBetween;
use CodeHappy\DataLayer\Queries\Conditions\IsNull;
use CodeHappy\DataLayer\Queries\Conditions\NotNull;
use CodeHappy\DataLayer\Queries\EagerLoading\With;
use CodeHappy\DataLayer\Queries\Joins\InnerJoin;
use CodeHappy\DataLayer\Queries\Joins\LeftJoin;
use CodeHappy\DataLayer\Queries\Joins\RightJoin;
use CodeHappy\DataLayer\Queries\Show\Offset;
use CodeHappy\DataLayer\Queries\Show\Limit;
use CodeHappy\DataLayer\Queries\Show\Select;
use CodeHappy\DataLayer\Queries\SoftDeletes\OnlyTrashed;
use CodeHappy\DataLayer\Queries\SoftDeletes\WithTrashed;
use CodeHappy\DataLayer\Queries\Sorting\OrderBy;
use CodeHappy\DataLayer\Queries\Summarized\GroupBy;
use CodeHappy\DataLayer\Queries\Summarized\Having;

class Factory
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * @var \CodeHappy\DataLayer\Contracts\RepositoryInterface
     */
    protected $repository;

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \CodeHappy\DataLayer\Contracts\RepositoryInterface $repository
     * @return $this
     */
    public function load(Builder $builder, RepositoryInterface $repository): self
    {
        $this->builder = $builder;
        $this->repository = $repository;

        return $this;
    }

    /**
     * Apply where condition
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function where(): Builder
    {
        return (new Where($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply where condition
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function whereIn(): Builder
    {
        return (new WhereIn($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply where condition
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function whereNotIn(): Builder
    {
        return (new WhereNotIn($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply between condition
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function between(): Builder
    {
        return (new Between($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply not between condition
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function notBetween(): Builder
    {
        return (new NotBetween($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply null condition
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function isNull(): Builder
    {
        return (new IsNull($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply not null condition
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function isNotNull(): Builder
    {
        return (new NotNull($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply inner join relationship
     *
     * @param string $table
     * @param \Closure|array|string $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function innerJoin(string $table, $relations): Builder
    {
        return (new InnerJoin($this->builder, $this->repository))
            ->handle($table, $relations);
    }

    /**
     * Apply left join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function leftJoin(string $table, $relations): Builder
    {
        return (new LeftJoin($this->builder, $this->repository))
            ->handle($table, $relations);
    }

    /**
     * Apply right join relationship
     *
     * @param string $table
     * @param \Closure|array $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function rightJoin(string $table, $relations): Builder
    {
        return (new RightJoin($this->builder, $this->repository))
            ->handle($table, $relations);
    }

    /**
     * Apply offset in query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function offset(): Builder
    {
        return (new Offset($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply limit in query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function limit(): Builder
    {
        return (new Limit($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply select fields
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function select(): Builder
    {
        return (new Select($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply sorting
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orderBy(): Builder
    {
        return (new OrderBy($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply group by
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function groupBy(): Builder
    {
        return (new GroupBy($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply having
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function having(): Builder
    {
        return (new Having($this->builder, $this->repository))
            ->handle(...func_get_args());
    }

    /**
     * Apply eager loading
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function with(): Builder
    {
        return (new With($this->builder, $this->repository))
            ->handle(...func_get_args());
    }
}
