<?php

namespace CodeHappy\DataLayer\Tests\Queries\Conditions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Conditions\Where as Query;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class WhereTest extends TestCase
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
    public function it_handles_should_be_successful($method, $params, $query): void
    {
        DB::shouldReceive('raw')
            ->with($params[0])
            ->once()
            ->andReturn((string) $params[0]);

        $this->builder
            ->shouldReceive($method)
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
                'where',
                ['id', '=', 1, 'AND'],
                ['id', '=', 1],
            ],
            [
                'where',
                ['id', '=', 1, 'AND'],
                ['id', '=', 1, 'AND'],
            ],
            [
                'where',
                ['id', '=', 1, 'OR'],
                ['id', '=', 1, 'OR'],
            ],
            [
                'where',
                ['id', '<>', 1, 'AND'],
                ['id', '<>', 1],
            ],
            [
                'where',
                ['id', '<>', 1, 'AND'],
                ['id', '<>', 1, 'AND'],
            ],
            [
                'where',
                ['id', '<>', 1, 'OR'],
                ['id', '<>', 1, 'OR'],
            ],
            [
                'where',
                ['name', 'LIKE', 'Joe%', 'AND'],
                ['name', 'LIKE', 'Joe%'],
            ],
            [
                'where',
                ['name', 'LIKE', '%Doe', 'AND'],
                ['name', 'LIKE', '%Doe', 'AND'],
            ],
            [
                'where',
                ['name', 'LIKE', '%oe Do%', 'OR'],
                ['name', 'LIKE', '%oe Do%', 'OR'],
            ],
            [
                'where',
                ['name', 'NOT LIKE', 'Mary%', 'AND'],
                ['name', 'NOT LIKE', 'Mary%'],
            ],
            [
                'where',
                ['name', 'NOT LIKE', '%Jane', 'AND'],
                ['name', 'NOT LIKE', '%Jane', 'AND'],
            ],
            [
                'where',
                ['name', 'NOT LIKE', '%ary Jan%', 'OR'],
                ['name', 'NOT LIKE', '%ary Jan%', 'OR'],
            ],
            [
                'whereRaw',
                ["name LIKE '%oe Do%'"],
                ["name LIKE '%oe Do%'"],
            ],
            [
                'whereRaw',
                ['id <> 200 AND age > 20'],
                ['id <> 200 AND age > 20'],
            ],
            [
                'whereRaw',
                ["activated_at IS NOT NULL AND CAST(registered_at AS DATE) = '2019-11-29'"],
                ["activated_at IS NOT NULL AND CAST(registered_at AS DATE) = '2019-11-29'"],
            ],
            [
                'whereRaw',
                ['price BETWEEN 100 AND 200 OR category_id IN (1, 2, 3)'],
                ['price BETWEEN 100 AND 200 OR category_id IN (1, 2, 3)'],
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
    public function it_raises_an_exception_should_be_successful($query): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->query->handle(...$query);
    }

    /**
     * @return array[][]
     */
    public function additionExceptionProvider(): array
    {
        return [
            [
                ['test1', '=', 'test2', 'test3'],
                ['test1', '=', 'test2', '!=', 'test3'],
            ],
        ];
    }
}
