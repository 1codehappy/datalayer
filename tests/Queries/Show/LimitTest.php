<?php

namespace CodeHappy\DataLayer\Tests\Queries\Show;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Show\Limit as Query;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class LimitTest extends TestCase
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
    public function it_handles_should_be_successful($limit, $param): void
    {
        $this->builder
            ->shouldReceive('limit')
            ->with($limit)
            ->once()
            ->andReturn($this->builder);
        $this->assertInstanceOf(Builder::class, $this->query->handle($param));
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                50, 0,
            ],
            [
                1, 1,
            ],
            [
                10, 10,
            ],
            [
                25, 25,
            ],
            [
                50, 50,
            ],
            [
                75, 75,
            ],
            [
                100, 100,
            ],
            [
                100, 101,
            ],
            [
                100, 1234,
            ],
        ];
    }

    /**
     * @test
     */
    public function it_handles_without_params_should_be_successful(): void
    {
        $this->builder
            ->shouldReceive('limit')
            ->with(50)
            ->once()
            ->andReturn($this->builder);
        $this->assertInstanceOf(Builder::class, $this->query->handle());
    }

    /**
     * @test
     * @dataProvider additionExceptionProvider
     */
    public function it_raises_an_exception_should_be_successful($params): void
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
                [
                    1, 1,
                ],
            ],
            [
                [
                    'test',
                ],
            ],
            [
                [
                    'test1', 'test2',
                ],
            ],
        ];
    }
}
