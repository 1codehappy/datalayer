<?php

namespace CodeHappy\DataLayer\Tests\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class FactoryHavingTest extends TestCase
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
    public function it_creates_an_instance_of_having_should_be_successful(
        $args,
        $params
    ): void {
        DB::shouldReceive('raw')
            ->with($args[0])
            ->once()
            ->andReturn((string) $args[0]);

        $this->builder
            ->shouldReceive('having')
            ->with(...$args)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            Builder::class,
            $this->factory->having(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['COUNT(id)', '>', 1],
                ['COUNT(id)', '>', 1],
            ],
            [
                ['SUM(price)', '<=', 250.00],
                ['SUM(price)', '<=', 250.00],
            ],
        ];
    }

    /**
     * @test
     */
    public function it_raises_an_exception_without_params_should_be_successful(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->having();
    }

    /**
     * @test
     * @dataProvider additionExceptionProvider
     */
    public function it_raises_an_exception_should_be_successful($params): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->having(...$params);
    }

    /**
     * @return array[][]
     */
    public function additionExceptionProvider(): array
    {
        return [
            [
                ['COUNT(id)'],
            ],
            [
                ['COUNT(id)', '>', 1, 'AND'],
            ],
            [
                ['SUM(price)', '<=', 250.00, 'OR'],
            ],
        ];
    }
}
