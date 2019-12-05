<?php

namespace CodeHappy\DataLayer\Tests\Traits\Queries;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use CodeHappy\DataLayer\Contracts\Queries\ConditionInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class RepositoryWhereTest extends TestCase
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
            ConditionInterface
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
    public function it_is_where_should_be_successful($params): void
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
            ->with(...$params)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            ConditionInterface::class,
            $this->repository
                ->where(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['id = 1'],
            ],
            [
                ['id = 1'],
            ],
            [
                ['id = 1'],
            ],
            [
                ['id <>  1'],
            ],
            [
                ['id <> 1'],
            ],
            [
                ['id <> 1'],
            ],
            [
                ['id != 1'],
            ],
            [
                ['id != 1'],
            ],
            [
                ['id != 1'],
            ],
            [
                ['email LIKE a%'],
            ],
            [
                ['email LIKE %@gmail.com'],
            ],
            [
                ['email LIKE %test%'],
            ],
            [
                ['email NOT LIKE a%'],
            ],
            [
                ['email NOT LIKE %@gmail.com'],
            ],
            [
                ['email NOT LIKE %test% OR'],
            ],
            [
                ['name NOT LIKE Mary%'],
            ],
            [
                ['name NOT LIKE %Jane'],
            ],
            [
                ['name NOT LIKE %ary Jan%'],
            ],
            [
                ["name LIKE '%oe Do%'"],
            ],
            [
                ['id <> 200 AND age > 20'],
            ],
            [
                ["activated_at IS NOT NULL AND CAST(registered_at AS DATE) = '2019-11-29'"],
            ],
            [
                ['price BETWEEN 100 AND 200 OR category_id IN (1, 2, 3)'],
            ],
        ];
    }
}
