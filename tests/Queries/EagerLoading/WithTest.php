<?php

namespace CodeHappy\DataLayer\Tests\Queries\EagerLoading;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\EagerLoading\With as Query;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class WithTest extends TestCase
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
        $this->builder
            ->shouldReceive('with')
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
                [
                    ['customers'],
                ],
                ['customers'],
            ],
            [
                [
                    ['customers'],
                ],
                [
                    ['customers'],
                ],
            ],
            [
                [
                    ['users', 'roles'],
                ],
                ['users', 'roles'],
            ],
            [
                [
                    ['users', 'roles'],
                ],
                [
                    ['users', 'roles'],
                ],
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
}
