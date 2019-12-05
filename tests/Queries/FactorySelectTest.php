<?php

namespace CodeHappy\DataLayer\Tests\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class FactorySelectTest extends TestCase
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
    public function it_creates_an_instance_of_select_should_be_successful(
        $args,
        $params
    ): void {
        foreach ($args[0] as $arg) {
            DB::shouldReceive('raw')
                ->with($arg)
                ->once()
                ->andReturn((string) $arg);
        }

        $this->builder
            ->shouldReceive('select')
            ->with(...$args)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            Builder::class,
            $this->factory->select(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                [
                    ['*'],
                ],
                ['*'],
            ],
            [
                [
                    ['*'],
                ],
                [
                    ['*'],
                ],
            ],
            [
                [
                    ['id', 'name', 'email', 'created_at', 'updated_at'],
                ],
                ['id', 'name', 'email', 'created_at', 'updated_at'],
            ],
            [
                [
                    ['id', 'name', 'email', 'created_at', 'updated_at'],
                ],
                [
                    ['id', 'name', 'email', 'created_at', 'updated_at'],
                ],
            ],
            [
                [
                    ['customer_id', 'SUM(total_payed) AS total_payed'],
                ],
                ['customer_id', 'SUM(total_payed) AS total_payed'],
            ],
            [
                [
                    ['customer_id', 'SUM(total_payed) AS total_payed'],
                ],
                [
                    ['customer_id', 'SUM(total_payed) AS total_payed'],
                ],
            ],
            [
                [
                    ['orders.id', 'customers.email', 'orders.date', 'orders.total'],
                ],
                ['orders.id', 'customers.email', 'orders.date', 'orders.total'],
            ],
            [
                [
                    ['orders.id', 'customers.email', 'orders.date', 'orders.total'],
                ],
                [
                    ['orders.id', 'customers.email', 'orders.date', 'orders.total'],
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
        $this->factory->select();
    }
}
