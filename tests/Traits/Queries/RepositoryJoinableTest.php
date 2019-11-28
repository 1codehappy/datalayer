<?php

namespace CodeHappy\DataLayer\Tests\Traits\Queries;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use CodeHappy\DataLayer\Contracts\Queries\JoinInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;
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

        $this->app = Mockery::mock(App::class);
        $this->app
            ->shouldReceive('make')
            ->with(Model::class)
            ->once()
            ->andReturn($this->model);

        $this->repository = new class($this->app) extends Repository implements
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
        QueryFactory::shouldReceive('innerJoin')
            ->shouldReceive(...$params)
            ->once()
            ->andReturn($this->builder);

        $actual = $this->repository
            ->innerJoin(...$params);
        $this->assertInstanceOf(JoinInterface::class, $actual);
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_creates_left_join_relationship_should_be_successful($params): void
    {
        QueryFactory::shouldReceive('leftJoin')
            ->shouldReceive(...$params)
            ->once()
            ->andReturn($this->builder);

        $actual = $this->repository
            ->leftJoin(...$params);
        $this->assertInstanceOf(JoinInterface::class, $actual);
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_creates_right_join_relationship_should_be_successful($params): void
    {
        QueryFactory::shouldReceive('rightJoin')
            ->shouldReceive(...$params)
            ->once()
            ->andReturn($this->builder);

        $actual = $this->repository
            ->rightJoin(...$params);
        $this->assertInstanceOf(JoinInterface::class, $actual);
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_tests_the_alias_should_be_successful($params): void
    {
        QueryFactory::shouldReceive('innerJoin')
            ->shouldReceive(...$params)
            ->once()
            ->andReturn($this->builder);

        $actual = $this->repository
            ->join(...$params);
        $this->assertInstanceOf(JoinInterface::class, $actual);
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
                    'order_items',
                    [
                        'orders.id' => 'order_items.order_id',
                        'products.id' => 'order_items.product_id',
                    ],
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
                    function ($join) {
                        $join->on('orders.id', '=', 'order_items.order_id')
                            ->on('products.id', '=', 'order_items.product_id');
                    },
                ],
            ],
        ];
    }
}
