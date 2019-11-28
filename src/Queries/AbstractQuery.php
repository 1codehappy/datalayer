<?php

namespace CodeHappy\DataLayer\Queries;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;

abstract class AbstractQuery
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
     * @return void
     */
    public function __construct(Builder $builder, RepositoryInterface $repository)
    {
        $this->builder = $builder;
        $this->repository = $repository;
    }

    /**
     * Handle condition
     *
     * @abstract
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function handle(): Builder;
}
