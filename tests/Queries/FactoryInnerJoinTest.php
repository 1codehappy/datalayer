<?php

namespace CodeHappy\DataLayer\Tests\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class FactoryInnerJoinTest extends TestCase
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
    public function it_creates_an_instance_of_group_by_should_be_successful(
        $args,
        $params
    ): void {
        foreach ($args as $arg) {
            DB::shouldReceive('raw')
                ->with($arg)
                ->once()
                ->andReturn((string) $arg);
        }

        $this->builder
            ->shouldReceive('groupBy')
            ->with($args)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            Builder::class,
            $this->factory->groupBy(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['id'],
                ['id'],
            ],
            [
                ['id'],
                [
                    ['id'],
                ],
            ],
            [
                ['1'],
                [1],
            ],
            [
                ['1'],
                [
                    [1],
                ],
            ],
            [
                ['customer_id', '2'],
                ['customer_id', '2'],
            ],
            [
                ['customer_id', '2'],
                [
                    ['customer_id', '2'],
                ],
            ],
            [
                ['orders.customer_id', 'orders.date'],
                ['orders.customer_id', 'orders.date'],
            ],
            [
                ['orders.customer_id', 'orders.date'],
                [
                    ['orders.customer_id', 'orders.date'],
                ],
            ],
        ];
    }

    /**
     * @test
     */
    public function it_raises_an_exception_should_be_successful(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->groupBy();
    }
}
