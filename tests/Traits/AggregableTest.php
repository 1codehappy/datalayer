<?php

namespace CodeHappy\DataLayer\Tests\Traits;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class AggregableTest extends TestCase
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
     * @var \CodeHappy\DataLayer\Repository
     */
    protected $repository;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->builder  = Mockery::mock(Builder::class);
        $this->model    = Mockery::mock(Model::class);

        $this->app = Mockery::mock(App::class);
        $this->app
            ->shouldReceive('make')
            ->with(Model::class)
            ->once()
            ->andReturn($this->model);

        $this->repository = new class($this->app) extends Repository
        {
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
    public function it_counts_the_number_of_rows_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $expected = 3;
        $this->builder
            ->shouldReceive('count')
            ->once()
            ->andReturn($expected);

        $actual = $this->repository->count();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function it_sums_the_value_from_column_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $expected = 123.45;
        $this->builder
            ->shouldReceive('sum')
            ->with('price')
            ->once()
            ->andReturn($expected);

        $actual = $this->repository->sum('price');

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function it_gets_the_max_value_from_column_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $expected = 1234567890;
        $this->builder
            ->shouldReceive('max')
            ->with('pageviews')
            ->once()
            ->andReturn($expected);

        $actual = $this->repository->max('pageviews');

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function it_gets_the_min_value_from_column_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $expected = '1978-09-17';
        $this->builder
            ->shouldReceive('min')
            ->with('birth_date')
            ->once()
            ->andReturn($expected);

        $actual = $this->repository->min('birth_date');

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function it_gets_the_average_from_column_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $expected = 22.5;
        $this->builder
            ->shouldReceive('avg')
            ->with('age')
            ->once()
            ->andReturn($expected);

        $actual = $this->repository->avg('age');

        $this->assertSame($expected, $actual);
    }
}
