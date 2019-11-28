<?php

namespace CodeHappy\DataLayer\Tests\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class FactoryNullTest extends TestCase
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
    public function it_creates_an_instance_of_is_null_should_be_successful(
        $args,
        $params
    ): void {
        DB::shouldReceive('raw')
            ->with($args[0])
            ->once()
            ->andReturn((string) $args[0]);

        $this->builder
            ->shouldReceive('whereNull')
            ->with(...$args)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            Builder::class,
            $this->factory->null(...$params)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['deleted_at', 'AND'],
                ['deleted_at'],
            ],
            [
                ['deleted_at', 'AND'],
                ['deleted_at', 'AND'],
            ],
            [
                ['deleted_at', 'OR'],
                ['deleted_at', 'OR'],
            ],
        ];
    }

    /**
     * @test
     */
    public function it_raises_an_exception_without_params_should_be_successful(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->null();
    }

    /**
     * @test
     * @dataProvider additionExceptionProvider
     */
    public function it_raises_an_exception_should_be_successful($params): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->null(...$params);
    }

    public function additionExceptionProvider(): array
    {
        return [
            [
                ['test1', 'test2', 'test3'],
            ],
            [
                ['test1', 'test2', 'test3'],
            ],
            [
                ['test1', 'test2', 'test3', 'AND'],
            ],
            [
                ['test1', 'test2', 'test3', 'AND'],
            ],
            [
                ['test1', 'test2', 'test3', 'OR'],
            ],
            [
                ['test1', 'test2', 'test3', 'OR'],
            ],
        ];
    }
}
