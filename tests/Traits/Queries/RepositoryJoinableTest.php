<?php

namespace CodeHappy\DataLayer\Tests\Traits\Queries;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use CodeHappy\DataLayer\Contracts\Queries\JoinInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class RepositoryJoinableTest extends TestCase
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

        $this->repository = new class ($this->app) extends Repository implements
            JoinInterface
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
    public function it_creates_inner_join_relationship_should_be_successful($params): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        QueryFactory::shouldReceive('load')
            ->with($this->builder, $this->repository)
            ->twice()
            ->andReturn($this->factory);

        $this->factory
            ->shouldReceive('innerJoin')
            ->with(...$params)
            ->twice()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            JoinInterface::class,
            $this->repository->innerJoin(...$params)
        );

        $this->assertInstanceOf(
            JoinInterface::class,
            $this->repository->join(...$params)
        );
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_creates_left_join_relationship_should_be_successful($params): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        QueryFactory::shouldReceive('load')
            ->with($this->builder, $this->repository)
            ->twice()
            ->andReturn($this->factory);

        $this->factory
            ->shouldReceive('leftJoin')
            ->with(...$params)
            ->twice()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            JoinInterface::class,
            $this->repository->leftJoin(...$params)
        );

        $this->assertInstanceOf(
            JoinInterface::class,
            $this->repository->ljoin(...$params)
        );
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_creates_right_join_relationship_should_be_successful($params): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        QueryFactory::shouldReceive('load')
            ->with($this->builder, $this->repository)
            ->twice()
            ->andReturn($this->factory);

        $this->factory
            ->shouldReceive('rightJoin')
            ->with(...$params)
            ->twice()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            JoinInterface::class,
            $this->repository->rightJoin(...$params)
        );

        $this->assertInstanceOf(
            JoinInterface::class,
            $this->repository->rjoin(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                [
                    'customers',
                    [
                        'customers.id' => 'orders.customer_id',
                    ],
                ],
            ],
            [
                [
                    'customers',
                    'customers.id = orders.customer_id',
                ],
            ],
            [
                [
                    'customers',
                    function ($join) {
                        $join->on('customers.id', '=', 'orders.customer_id');
                    },
                ],
            ],
            [
                [
                    'order_items',
                    'orders.id = order_items.order_id AND products.id = order_items.product_id',
                ],
            ],
            [
                [
                    'order_items',
                    [
                        'orders.id' => 'order_items.order_id',
                        'products.id' => 'order_items.product_id',
                    ],
                ],
            ],
            [
                [
                    'order_items',
                    function ($join) {
                        $join->on('orders.id', '=', 'order_items.order_id')
                            ->on('products.id', '=', 'order_items.product_id');
                    },
                ],
            ],
        ];
    }
}
