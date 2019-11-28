<?php

namespace CodeHappy\DataLayer\Tests\Traits\Queries;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use CodeHappy\DataLayer\Contracts\Queries\ShowInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class RepositoryShowTest extends TestCase
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
            ShowInterface
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
    public function it_select_fields_should_be_successful($params): void
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
            ->shouldReceive('select')
            ->with(...$params)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            ShowInterface::class,
            $this->repository->select(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['id', 'name', 'email', 'registered_at'],
            ],
            [
                [
                    ['id', 'name', 'email', 'registered_at'],
                ],
            ],
            [
                ['orders.id', 'orders.customer_id', 'customers.email'],
            ],
            [
                [
                    ['orders.id', 'orders.customer_id', 'customers.email'],
                ],
            ],
            [
                ['orders.id AS order_id', 'SUM(price) AS total'],
            ],
        ];
    }

    /**
     * @test
     */
    public function it_takes_a_limit_of_rows_should_be_successful(): void
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
            ->shouldReceive('limit')
            ->with(1234)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            ShowInterface::class,
            $this->repository->limit(1234)
        );
    }

    /**
     * @test
     */
    public function it_skips_a_number_of_rows_should_be_successful(): void
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
            ->shouldReceive('offset')
            ->with(13)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            ShowInterface::class,
            $this->repository->offset(13)
        );
    }
}
