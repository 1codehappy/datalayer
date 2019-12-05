<?php

namespace CodeHappy\DataLayer\Tests\Traits;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Traits\Debugable;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class DebugableTest extends TestCase
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
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \CodeHappy\DataLayer\Repository
     */
    protected $repository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->builder  = Mockery::mock(Builder::class);
        $this->criteria = Mockery::mock(Criteria::class);
        $this->model    = Mockery::mock(Model::class);

        $this->app = Mockery::mock(App::class);
        $this->app
            ->shouldReceive('make')
            ->with(Model::class)
            ->once()
            ->andReturn($this->model);

        $this->repository = new class ($this->app) extends Repository
        {
            use Debugable;

            /**
             * {@inheritDoc}
             */
            protected $preventOverwriting = false;

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
    public function it_prints_the_sql_from_query_should_be_successful(
        $bindings,
        $sql,
        $expected
    ): void {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);
        $this->builder
            ->shouldReceive('getBindings')
            ->once()
            ->andReturn($bindings);
        $this->builder
            ->shouldReceive('toSql')
            ->once()
            ->andReturn($sql);

        $actual = $this->repository
            ->toSql();
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                [],
                'SELECT * FROM users',
                'SELECT * FROM users;',
            ],
            [
                ['id' => 1],
                'SELECT * FROM users WHERE id = ?',
                "SELECT * FROM users WHERE id = '1';",
            ],
        ];
    }
}
