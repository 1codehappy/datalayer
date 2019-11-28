<?php

namespace CodeHappy\DataLayer\Tests\Queries\Summarized;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Summarized\Having as Query;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class HavingTest extends TestCase
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
            ->shouldReceive('having')
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
        $this->query->handle();
    }

    /**
     * @test
     * @dataProvider additionExceptionProvider
     */
    public function it_raises_an_exception_should_be_successful($query): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->query->handle(... $query);
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
