<?php

namespace CodeHappy\DataLayer\Tests\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class FactoryWhereTest extends TestCase
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * @var \CodeHappy\DataLayer\Contracts\RepositoryInterface
     */
    protected $repository;

    /**
     * @var \CodeHappy\DataLayer\Queries\Factory
     */
    protected $factory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->builder      = Mockery::mock(Builder::class);
        $this->repository   = Mockery::mock(RepositoryInterface::class);

        $this->factory = new Factory;
        $this->factory->load($this->builder, $this->repository);
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_creates_an_instance_of_where_should_be_successful(
        $args,
        $params
    ): void {
        DB::shouldReceive('raw')
            ->with($args[0])
            ->once()
            ->andReturn((string) $args[0]);

        $this->builder
            ->shouldReceive('where')
            ->with(...$args)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            Builder::class,
            $this->factory->where(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['id', '=', 1, 'AND'],
                ['id', '=', 1],
            ],
            [
                ['id', '=', 1, 'AND'],
                ['id', '=', 1, 'AND'],
            ],
            [
                ['id', '=', 1, 'OR'],
                ['id', '=', 1, 'OR'],
            ],
            [
                ['id', '<>', 1, 'AND'],
                ['id', '<>', 1],
            ],
            [
                ['id', '<>', 1, 'AND'],
                ['id', '<>', 1, 'AND'],
            ],
            [
                ['id', '<>', 1, 'OR'],
                ['id', '<>', 1, 'OR'],
            ],
            [
                ['id', '!=', 1, 'AND'],
                ['id', '!=', 1],
            ],
            [
                ['id', '!=', 1, 'AND'],
                ['id', '!=', 1, 'AND'],
            ],
            [
                ['id', '!=', 1, 'OR'],
                ['id', '!=', 1, 'OR'],
            ],
            [
                ['email', 'LIKE', 'a%', 'AND'],
                ['email', 'LIKE', 'a%'],
            ],
            [
                ['email', 'LIKE', '%@gmail.com', 'AND'],
                ['email', 'LIKE', '%@gmail.com', 'AND'],
            ],
            [
                ['email', 'LIKE', '%test%', 'OR'],
                ['email', 'LIKE', '%test%', 'OR'],
            ],
            [
                ['email', 'NOT LIKE', 'a%', 'AND'],
                ['email', 'NOT LIKE', 'a%'],
            ],
            [
                ['email', 'NOT LIKE', '%@gmail.com', 'AND'],
                ['email', 'NOT LIKE', '%@gmail.com', 'AND'],
            ],
            [
                ['email', 'NOT LIKE', '%test%', 'OR'],
                ['email', 'NOT LIKE', '%test%', 'OR'],
            ],
        ];
    }
}
