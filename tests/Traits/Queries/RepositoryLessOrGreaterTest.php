<?php

namespace CodeHappy\DataLayer\Tests\Traits\Queries;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use CodeHappy\DataLayer\Contracts\Queries\ConditionInterface;
use CodeHappy\DataLayer\Facades\QueryFactory;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class RepositoryLessOrGreaterTest extends TestCase
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
     * @dataProvider isGreaterThan
     */
    public function it_is_greater_than_should_be_successful($args, $params): void
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
            $this->repository->isGreaterThan(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function isGreaterThan(): array
    {
        return [
            [
                ['age', '>', 17, 'AND'],
                ['age', 17],
            ],
            [
                ['age', '>', 17, 'AND'],
                ['age', 17, 'AND'],
            ],
            [
                ['age', '>', 17, 'OR'],
                ['age', 17, 'OR'],
            ],
            [
                ['salary', '>', 123.45, 'AND'],
                ['salary', 123.45],
            ],
            [
                ['salary', '>', 123.45, 'AND'],
                ['salary', 123.45, 'AND'],
            ],
            [
                ['salary', '>', 123.45, 'OR'],
                ['salary', 123.45, 'OR'],
            ],
            [
                ['birth_date', '>', '2001-12-04', 'AND'],
                ['birth_date', '2001-12-04'],
            ],
            [
                ['birth_date', '>', '2001-12-04', 'AND'],
                ['birth_date', '2001-12-04', 'AND'],
            ],
            [
                ['birth_date', '>', '2001-12-04', 'OR'],
                ['birth_date', '2001-12-04', 'OR'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider isGreaterThanEqualTo
     */
    public function it_is_greater_than_equal_to_should_be_successful($args, $params): void
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
            $this->repository->isGreaterThanEqualTo(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function isGreaterThanEqualTo(): array
    {
        return [
            [
                ['age', '>=', 17, 'AND'],
                ['age', 17],
            ],
            [
                ['age', '>=', 17, 'AND'],
                ['age', 17, 'AND'],
            ],
            [
                ['age', '>=', 17, 'OR'],
                ['age', 17, 'OR'],
            ],
            [
                ['salary', '>=', 123.45, 'AND'],
                ['salary', 123.45],
            ],
            [
                ['salary', '>=', 123.45, 'AND'],
                ['salary', 123.45, 'AND'],
            ],
            [
                ['salary', '>=', 123.45, 'OR'],
                ['salary', 123.45, 'OR'],
            ],
            [
                ['birth_date', '>=', '2001-12-04', 'AND'],
                ['birth_date', '2001-12-04'],
            ],
            [
                ['birth_date', '>=', '2001-12-04', 'AND'],
                ['birth_date', '2001-12-04', 'AND'],
            ],
            [
                ['birth_date', '>=', '2001-12-04', 'OR'],
                ['birth_date', '2001-12-04', 'OR'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider isLessThan
     */
    public function it_is_less_than_should_be_successful($args, $params): void
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
            $this->repository->isLessThan(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function isLessThan(): array
    {
        return [
            [
                ['age', '<', 17, 'AND'],
                ['age', 17],
            ],
            [
                ['age', '<', 17, 'AND'],
                ['age', 17, 'AND'],
            ],
            [
                ['age', '<', 17, 'OR'],
                ['age', 17, 'OR'],
            ],
            [
                ['salary', '<', 123.45, 'AND'],
                ['salary', 123.45],
            ],
            [
                ['salary', '<', 123.45, 'AND'],
                ['salary', 123.45, 'AND'],
            ],
            [
                ['salary', '<', 123.45, 'OR'],
                ['salary', 123.45, 'OR'],
            ],
            [
                ['birth_date', '<', '2001-12-04', 'AND'],
                ['birth_date', '2001-12-04'],
            ],
            [
                ['birth_date', '<', '2001-12-04', 'AND'],
                ['birth_date', '2001-12-04', 'AND'],
            ],
            [
                ['birth_date', '<', '2001-12-04', 'OR'],
                ['birth_date', '2001-12-04', 'OR'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider isLessThanEqualTo
     */
    public function it_is_less_than_equal_to_should_be_successful($args, $params): void
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
            $this->repository->isLessThanEqualTo(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function isLessThanEqualTo(): array
    {
        return [
            [
                ['age', '<=', 17, 'AND'],
                ['age', 17],
            ],
            [
                ['age', '<=', 17, 'AND'],
                ['age', 17, 'AND'],
            ],
            [
                ['age', '<=', 17, 'OR'],
                ['age', 17, 'OR'],
            ],
            [
                ['salary', '<=', 123.45, 'AND'],
                ['salary', 123.45],
            ],
            [
                ['salary', '<=', 123.45, 'AND'],
                ['salary', 123.45, 'AND'],
            ],
            [
                ['salary', '<=', 123.45, 'OR'],
                ['salary', 123.45, 'OR'],
            ],
            [
                ['birth_date', '<=', '2001-12-04', 'AND'],
                ['birth_date', '2001-12-04'],
            ],
            [
                ['birth_date', '<=', '2001-12-04', 'AND'],
                ['birth_date', '2001-12-04', 'AND'],
            ],
            [
                ['birth_date', '<=', '2001-12-04', 'OR'],
                ['birth_date', '2001-12-04', 'OR'],
            ],
        ];
    }

    /**
     * @test
     */
    public function it_test_the_aliases_should_be_successful(): void
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
            ->with('id', '>', 100, 'AND')
            ->once()
            ->andReturn($this->builder);

        $this->factory
            ->shouldReceive('where')
            ->with('id', '<', 200, 'AND')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            ConditionInterface::class,
            $this->repository->after('id', 100)->before('id', 200)
        );
    }

    /**
     * @test
     */
    public function it_test_the_aliases_with_equal_to_should_be_successful(): void
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
            ->with('id', '>=', 100, 'AND')
            ->once()
            ->andReturn($this->builder);

        $this->factory
            ->shouldReceive('where')
            ->with('id', '<=', 200, 'AND')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            ConditionInterface::class,
            $this->repository->since('id', 100)->until('id', 200)
        );
    }
}
