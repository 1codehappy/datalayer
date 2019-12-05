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

class RepositoryLikeOrNotTest extends TestCase
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
    public function it_is_like_that_should_be_successful($args, $params): void
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
            ->with(...$args)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            ConditionInterface::class,
            $this->repository->like(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['name', 'LIKE', 'test', 'AND'],
                ['name', 'test'],
            ],
            [
                ['name', 'LIKE', 'test', 'AND'],
                ['name', 'test', 'AND'],
            ],
            [
                ['name', 'LIKE', 'test', 'OR'],
                ['name', 'test', 'OR'],
            ],
            [
                ['email', 'LIKE', '%@gmail.com', 'AND'],
                ['email', '%@gmail.com'],
            ],
            [
                ['email', 'LIKE', '%@gmail.com', 'AND'],
                ['email', '%@gmail.com', 'AND'],
            ],
            [
                ['email', 'LIKE', '%@gmail.com', 'OR'],
                ['email', '%@gmail.com', 'OR'],
            ],
            [
                ['birth_date', 'LIKE', '%-10-%', 'AND'],
                ['birth_date', '%-10-%'],
            ],
            [
                ['birth_date', 'LIKE', '%-10-%', 'AND'],
                ['birth_date', '%-10-%', 'AND'],
            ],
            [
                ['birth_date', 'LIKE', '%-10-%', 'OR'],
                ['birth_date', '%-10-%', 'OR'],
            ],
            [
                ['mother_name', 'LIKE', 'A%', 'AND'],
                ['mother_name', 'A%'],
            ],
            [
                ['mother_name', 'LIKE', 'A%', 'AND'],
                ['mother_name', 'A%', 'AND'],
            ],
            [
                ['mother_name', 'LIKE', 'A%', 'OR'],
                ['mother_name', 'A%', 'OR'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider additionProviderNot
     */
    public function it_is_not_like_that_should_be_successful($args, $params): void
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
            ->with(...$args)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            ConditionInterface::class,
            $this->repository->notLike(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProviderNot(): array
    {
        return [
            [
                ['name', 'NOT LIKE', 'test', 'AND'],
                ['name', 'test'],
            ],
            [
                ['name', 'NOT LIKE', 'test', 'AND'],
                ['name', 'test', 'AND'],
            ],
            [
                ['name', 'NOT LIKE', 'test', 'OR'],
                ['name', 'test', 'OR'],
            ],
            [
                ['email', 'NOT LIKE', '%@gmail.com', 'AND'],
                ['email', '%@gmail.com'],
            ],
            [
                ['email', 'NOT LIKE', '%@gmail.com', 'AND'],
                ['email', '%@gmail.com', 'AND'],
            ],
            [
                ['email', 'NOT LIKE', '%@gmail.com', 'OR'],
                ['email', '%@gmail.com', 'OR'],
            ],
            [
                ['birth_date', 'NOT LIKE', '%-10-%', 'AND'],
                ['birth_date', '%-10-%'],
            ],
            [
                ['birth_date', 'NOT LIKE', '%-10-%', 'AND'],
                ['birth_date', '%-10-%', 'AND'],
            ],
            [
                ['birth_date', 'NOT LIKE', '%-10-%', 'OR'],
                ['birth_date', '%-10-%', 'OR'],
            ],
            [
                ['mother_name', 'NOT LIKE', 'A%', 'AND'],
                ['mother_name', 'A%'],
            ],
            [
                ['mother_name', 'NOT LIKE', 'A%', 'AND'],
                ['mother_name', 'A%', 'AND'],
            ],
            [
                ['mother_name', 'NOT LIKE', 'A%', 'OR'],
                ['mother_name', 'A%', 'OR'],
            ],
        ];
    }
}
