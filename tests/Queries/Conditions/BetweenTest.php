<?php

namespace CodeHappy\DataLayer\Tests\Queries\Conditions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Conditions\Between as Query;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class BetweenTest extends TestCase
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
     * @var Query
     */
    protected $query;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->builder    = Mockery::mock(Builder::class);
        $this->repository = Mockery::mock(RepositoryInterface::class);

        $this->query = new Query($this->builder, $this->repository);
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_handles_should_be_successful($params, $query): void
    {
        DB::shouldReceive('raw')
            ->with($params[0])
            ->once()
            ->andReturn((string) $params[0]);

        $this->builder
            ->shouldReceive('between')
            ->with(...$params)
            ->once()
            ->andReturn($this->builder);
        $this->assertInstanceOf(Builder::class, $this->query->handle(...$query));
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['age', [18, 33], 'AND'],
                ['age', 18, 33],
            ],
            [
                ['age', [18, 33], 'AND'],
                ['age', [18, 33]],
            ],
            [
                ['age', [18, 33], 'AND'],
                ['age', 18, 33, 'AND'],
            ],
            [
                ['age', [18, 33], 'AND'],
                ['age', [18, 33], 'AND'],
            ],
            [
                ['age', [18, 33], 'OR'],
                ['age', 18, 33, 'OR'],
            ],
            [
                ['age', [18, 33], 'OR'],
                ['age', [18, 33], 'OR'],
            ],
            [
                ['price', [100.01, 199.99], 'AND'],
                ['price', 100.01, 199.99],
            ],
            [
                ['price', [100.01, 199.99], 'AND'],
                ['price', [100.01, 199.99]],
            ],
            [
                ['price', [100.01, 199.99], 'AND'],
                ['price', 100.01, 199.99, 'AND'],
            ],
            [
                ['price', [100.01, 199.99], 'AND'],
                ['price', [100.01, 199.99], 'AND'],
            ],
            [
                ['price', [100.01, 199.99], 'OR'],
                ['price', 100.01, 199.99, 'OR'],
            ],
            [
                ['price', [100.01, 199.99], 'OR'],
                ['price', [100.01, 199.99], 'OR'],
            ],
            [
                ['registered_at', ['2000-01-01', '2000-12-31'], 'AND'],
                ['registered_at', '2000-01-01', '2000-12-31'],
            ],
            [
                ['registered_at', ['2000-01-01', '2000-12-31'], 'AND'],
                ['registered_at', ['2000-01-01', '2000-12-31']],
            ],
            [
                ['registered_at', ['2000-01-01', '2000-12-31'], 'AND'],
                ['registered_at', '2000-01-01', '2000-12-31', 'AND'],
            ],
            [
                ['registered_at', ['2000-01-01', '2000-12-31'], 'AND'],
                ['registered_at', ['2000-01-01', '2000-12-31'], 'AND'],
            ],
            [
                ['registered_at', ['2000-01-01', '2000-12-31'], 'OR'],
                ['registered_at', '2000-01-01', '2000-12-31', 'OR'],
            ],
            [
                ['registered_at', ['2000-01-01', '2000-12-31'], 'OR'],
                ['registered_at', ['2000-01-01', '2000-12-31'], 'OR'],
            ],
        ];
    }

    /**
     * @test
     */
    public function it_raises_an_exception_without_params_should_be_successful(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->query->handle();
    }

    /**
     * @test
     * @dataProvider additionExceptionProvider
     */
    public function it_handles_raise_an_exception_should_be_failed($params): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->query->handle(...$params);
    }

    /**
     * @return array[][]
     */
    public function additionExceptionProvider(): array
    {
        return [
            [
                ['test'],
            ],
            [
                ['test1', 'test2'],
            ],
            [
                ['test1', 'test2', 'test3', 'test4'],
            ],
            [
                ['test1', 'test2', 'test3', 'test4', 'test5'],
            ],
            [
                ['test1', ['test2'], 'test3'],
            ],
            [
                ['test1', ['test2', ['test3']]],
            ],
            [
                ['test1', 'test2', ['test3']],
            ],
        ];
    }
}
