<?php

namespace CodeHappy\DataLayer\Tests\Traits;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use CodeHappy\DataLayer\Facades\QueryFactory;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Traits\Debugable;
use CodeHappy\DataLayer\Traits\SoftDeletes;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class SoftDeletesTest extends TestCase
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \CodeHappy\DataLayer\Repository
     */
    protected $repository;

    /**
     * @var \CodeHappy\DataLayer\Queries\Factory
     */
    protected $factory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->builder  = Mockery::mock(Builder::class);
        $this->model    = Mockery::mock(Model::class);
        $this->factory  = Mockery::mock(Factory::class);

        $this->app = Mockery::mock(App::class);
        $this->app
            ->shouldReceive('make')
            ->with(Model::class)
            ->once()
            ->andReturn($this->model);

        $this->repository = new class ($this->app) extends Repository
        {
            use Debugable;
            use SoftDeletes;

            /**
             * @return $this
             */
            public function instance(): self
            {
                return $this;
            }

            /**
             * {@inheritDoc}
             */
            public function model(): string
            {
                return Model::class;
            }
        };
    }

    /**
     * @test
     */
    public function it_creates_an_instance_of_repository(): void
    {
        $this->assertInstanceOf(Repository::class, $this->repository->instance());
    }

    /**
     * @test
     */
    public function it_gets_model_class_should_be_successful(): void
    {
        $this->assertSame(Model::class, $this->repository->model());
    }

    /**
     * @test
     */
    public function it_returns_all_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('withTrashed')
            ->once()
            ->andReturn($this->builder);

        $expected = 'SELECT * FROM users;';
        $this->builder
            ->shouldReceive('toSql')
            ->once()
            ->andReturn($expected);

        $actual = $this->repository
            ->withTrashed()
            ->toSql();
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function it_returns_only_trashed_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('onlyTrashed')
            ->once()
            ->andReturn($this->builder);

        $expected = 'SELECT * FROM users WHERE deleted_at IS NOT NULL;';
        $this->builder
            ->shouldReceive('toSql')
            ->once()
            ->andReturn($expected);

        $actual = $this->repository
            ->onlyTrashed()
            ->toSql();
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function it_restores_trashed_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('restore')
            ->once()
            ->andReturn(true);

        $this->assertTrue($this->repository->restoreFromTrash());
    }

    /**
     * @test
     */
    public function it_does_not_restore_trashed_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('restore')
            ->once()
            ->andReturn(null);

        $this->assertNull($this->repository->restoreFromTrash());
    }
}
