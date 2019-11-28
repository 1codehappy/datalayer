<?php

namespace CodeHappy\DataLayer\Tests\Traits\Queries;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use CodeHappy\DataLayer\Contracts\Queries\SummaryInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class RepositorySummarizedTest extends TestCase
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

        $this->repository = new class($this->app) extends Repository implements
            SummaryInterface
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
     * @dataProvider additionProvider
     */
    public function it_groups_by_should_be_successful($params): void
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
            ->shouldReceive('groupBy')
            ->with(...$params)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            SummaryInterface::class,
            $this->repository->groupBy(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['order_id', 'product_id'],
            ],
            [
                [
                    ['order_id', 'product_id'],
                ],
            ],
            [
                [ 1 ],
            ],
            [
                [
                    [ 1, 2 ],
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider additionProviderForHaving
     */
    public function it_applies_having_should_be_successful($params, $query): void
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
            ->shouldReceive('having')
            ->with(...$query)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            SummaryInterface::class,
            $this->repository->having(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProviderForHaving(): array
    {
        return [
            [
                [ 'COUNT(id)' , '>', 1 ],
                [ 'COUNT(id)' , '>', 1, 'AND' ],
            ],
            [
                [ 'active' ],
                [ 'active' , null, null, 'AND' ],
            ],
            [
                [ 'SUM(price)', '<', 100, 'OR' ],
                [ 'SUM(price)', '<', 100, 'OR' ],
            ],
        ];
    }
}
