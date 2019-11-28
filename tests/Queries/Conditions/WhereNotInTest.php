<?php

namespace CodeHappy\DataLayer\Tests\Queries\Conditions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Conditions\WhereNotIn as Query;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class WhereNotInTest extends TestCase
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
    public function it_handles_should_be_successful($args, $params): void
    {
        DB::shouldReceive('raw')
            ->with($args[0])
            ->once()
            ->andReturn((string) $args[0]);

        $this->builder
            ->shouldReceive('whereNotIn')
            ->with(...$args)
            ->once()
            ->andReturn($this->builder);
        $this->assertInstanceOf(Builder::class, $this->query->handle(...$params));
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['id', [1, 2], 'AND'],
                ['id', 1, 2],
            ],
            [
                ['id', [1, 2], 'AND'],
                ['id', 1, 2, 'AND'],
            ],
            [
                ['id', [1, 2], 'OR'],
                ['id', 1, 2, 'OR'],
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
                ['status', ['active', 'inactive', 'canceled', 'locked'], 'AND'],
                ['status', ['active', 'inactive', 'canceled', 'locked']],
            ],
            [
                ['status', ['active', 'inactive', 'canceled', 'locked'], 'AND'],
                ['status', ['active', 'inactive', 'canceled', 'locked'], 'AND'],
            ],
            [
                ['status', ['active', 'inactive', 'canceled', 'locked'], 'OR'],
                ['status', ['active', 'inactive', 'canceled', 'locked'], 'OR'],
            ],
            [
                ['status', ['active', 'inactive', 'canceled', 'locked'], 'AND'],
                ['status', 'active', 'inactive', 'canceled', 'locked'],
            ],
            [
                ['status', ['active', 'inactive', 'canceled', 'locked'], 'AND'],
                ['status', 'active', 'inactive', 'canceled', 'locked', 'AND'],
            ],
            [
                ['status', ['active', 'inactive', 'canceled', 'locked'], 'OR'],
                ['status', 'active', 'inactive', 'canceled', 'locked', 'OR'],
            ],
            [
                ['id', [1], 'AND'],
                ['id', 1],
            ],
            [
                ['id', [1], 'AND'],
                ['id', 1, 'AND'],
            ],
            [
                ['id', [1], 'OR'],
                ['id', 1, 'OR'],
            ],
            [
                ['id', [1, 2, 3], 'AND'],
                ['id', 1, 2, 3],
            ],
            [
                ['id', [1, 2, 3], 'AND'],
                ['id', 1, 2, 3, 'AND'],
            ],
            [
                ['id', [1, 2, 3], 'OR'],
                ['id', 1, 2, 3, 'OR'],
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
     */
    public function it_raises_an_exception_should_be_successful(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->query->handle('test');
    }
}
