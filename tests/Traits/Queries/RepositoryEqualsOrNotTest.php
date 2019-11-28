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

class RepositoryEqualsOrNotTest extends TestCase
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
        $this->assertInstanceOf(ConditionInterface::class, $this->repository->instance());
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
    public function it_is_equal_to_should_be_successful($args, $params): void
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
            ->shouldReceive('where' . (is_array($args[1]) === true ? 'In' : ''))
            ->with(...$args)
            ->once()
            ->andReturn($this->builder);

        $actual = $this->repository->equals(...$params);
        $this->assertInstanceOf(ConditionInterface::class, $actual);
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['id', '=', 1, 'AND'],
                ['id', 1],
            ],
            [
                ['id', '=', 1, 'AND'],
                ['id', 1, 'AND'],
            ],
            [
                ['id', '=', 1, 'OR'],
                ['id', 1, 'OR'],
            ],
            [
                ['status', '=', 'active', 'AND'],
                ['status', 'active'],
            ],
            [
                ['status', '=', 'active', 'AND'],
                ['status', 'active', 'AND'],
            ],
            [
                ['status', '=', 'active', 'OR'],
                ['status', 'active', 'OR'],
            ],
            [
                ['id', [1, 2, 3], 'AND'],
                ['id', [1, 2, 3]],
            ],
            [
                ['id', [1, 2, 3], 'AND'],
                ['id', [1, 2, 3], 'AND'],
            ],
            [
                ['id', [1, 2, 3], 'OR'],
                ['id', [1, 2, 3], 'OR'],
            ],
            [
                ['status', ['inactive', 'canceled'], 'AND'],
                ['status', ['inactive', 'canceled']],
            ],
            [
                ['status', ['inactive', 'canceled'], 'AND'],
                ['status', ['inactive', 'canceled'], 'AND'],
            ],
            [
                ['status', ['inactive', 'canceled'], 'OR'],
                ['status', ['inactive', 'canceled'], 'OR'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider additionProviderNot
     */
    public function it_is_not_equal_to_should_be_successful($args, $params): void
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
            ->shouldReceive('where' . (is_array($args[1]) === true ? 'NotIn' : ''))
            ->with(...$args)
            ->once()
            ->andReturn($this->builder);

        $actual = $this->repository->notEquals(...$params);
        $this->assertInstanceOf(ConditionInterface::class, $actual);
    }

    /**
     * @return array[][]
     */
    public function additionProviderNot(): array
    {
        return [
            [
                ['id', '<>', 1, 'AND'],
                ['id', 1],
            ],
            [
                ['id', '<>', 1, 'AND'],
                ['id', 1, 'AND'],
            ],
            [
                ['id', '<>', 1, 'OR'],
                ['id', 1, 'OR'],
            ],
            [
                ['status', '<>', 'active', 'AND'],
                ['status', 'active'],
            ],
            [
                ['status', '<>', 'active', 'AND'],
                ['status', 'active', 'AND'],
            ],
            [
                ['status', '<>', 'active', 'OR'],
                ['status', 'active', 'OR'],
            ],
            [
                ['id', [1, 2, 3], 'AND'],
                ['id', [1, 2, 3]],
            ],
            [
                ['id', [1, 2, 3], 'AND'],
                ['id', [1, 2, 3], 'AND'],
            ],
            [
                ['id', [1, 2, 3], 'OR'],
                ['id', [1, 2, 3], 'OR'],
            ],
            [
                ['status', ['inactive', 'canceled'], 'AND'],
                ['status', ['inactive', 'canceled']],
            ],
            [
                ['status', ['inactive', 'canceled'], 'AND'],
                ['status', ['inactive', 'canceled'], 'AND'],
            ],
            [
                ['status', ['inactive', 'canceled'], 'OR'],
                ['status', ['inactive', 'canceled'], 'OR'],
            ],
        ];
    }
}
