<?php

namespace CodeHappy\DataLayer\Tests\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class FactoryWhereNotInTest extends TestCase
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

        $this->factory = new Factory();
        $this->factory->load($this->builder, $this->repository);
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_creates_an_instance_of_where_not_in_should_be_successful(
        $args,
        $params
    ): void {
        DB::shouldReceive('raw')
            ->with($args[0])
            ->once()
            ->andReturn((string) $args[0]);

        $this->builder
            ->shouldReceive('whereNotIn')
            ->with(...$args)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            Builder::class,
            $this->factory->whereNotIn(...$params)
        );
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
        $this->factory->whereNotIn();
    }

    /**
     * @test
     */
    public function it_raises_an_exception_should_be_successful(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->whereNotIn('test');
    }
}
