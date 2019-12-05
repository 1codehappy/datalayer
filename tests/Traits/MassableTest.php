<?php

namespace CodeHappy\DataLayer\Tests\Traits;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use CodeHappy\DataLayer\Facades\QueryFactory;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Traits\Massable;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class MassableTest extends TestCase
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
            use Massable;

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
    public function it_updates_data_from_query_should_be_successfull(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        QueryFactory::shouldReceive('load')
            ->with($this->builder, $this->repository)
            ->once()
            ->andReturn($this->factory);

        $this->factory
            ->shouldReceive('where')
            ->with('active IS NULL')
            ->once()
            ->andReturn($this->builder);

        $expected = 123;
        $this->builder
            ->shouldReceive('update')
            ->with(['active' => 1])
            ->once()
            ->andReturn($expected);

        $actual = $this->repository
            ->where('active IS NULL')
            ->updateAll(['active' => 1]);
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function it_deletes_rows_from_query_should_be_successfull(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        QueryFactory::shouldReceive('load')
            ->with($this->builder, $this->repository)
            ->once()
            ->andReturn($this->factory);

        $this->factory
            ->shouldReceive('where')
            ->with('active = 0')
            ->once()
            ->andReturn($this->builder);

        $expected = 456;
        $this->builder
            ->shouldReceive('delete')
            ->once()
            ->andReturn($expected);

        $actual = $this->repository
            ->where('active = 0')
            ->deleteAll();
        $this->assertSame($expected, $actual);
    }
}
