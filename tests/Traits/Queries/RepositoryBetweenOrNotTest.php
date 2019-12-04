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

class RepositoryBetweenOrNotTest extends TestCase
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
    public function it_is_between_should_be_successful($params): void
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
            ->shouldReceive('between')
            ->with(...$params)
            ->twice()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            ConditionInterface::class,
            $this->repository
                ->isBetween(...$params)
        );

        $this->assertInstanceOf(
            ConditionInterface::class,
            $this->repository
                ->between(...$params)
        );
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_is_not_between_should_be_successful($params): void
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
            ->shouldReceive('notBetween')
            ->with(...$params)
            ->twice()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            ConditionInterface::class,
            $this->repository
                ->isNotBetween(...$params)
        );

        $this->assertInstanceOf(
            ConditionInterface::class,
            $this->repository
                ->notBetween(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['price', 100.00, 200.00],
            ],
            [
                ['price', 100.00, 200.00, 'AND'],
            ],
            [
                ['price', 100.00, 200.00, 'OR'],
            ],
            [
                ['price', [100.00, 200.00]],
            ],
            [
                ['price', [100.00, 200.00], 'AND'],
            ],
            [
                ['price', [100.00, 200.00], 'OR'],
            ],
            [
                ['registered_at', '1980-01-01', '1999-12-31'],
            ],
            [
                ['registered_at', '1980-01-01', '1999-12-31', 'AND'],
            ],
            [
                ['registered_at', '1980-01-01', '1999-12-31', 'OR'],
            ],
            [
                ['registered_at', ['1980-01-01', '1999-12-31']],
            ],
            [
                ['registered_at', ['1980-01-01', '1999-12-31'], 'AND'],
            ],
            [
                ['registered_at', ['1980-01-01', '1999-12-31'], 'OR'],
            ],
        ];
    }
}
