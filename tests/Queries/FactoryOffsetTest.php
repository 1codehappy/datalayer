<?php

namespace CodeHappy\DataLayer\Tests\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Factory;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class FactoryOffsetTest extends TestCase
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
    public function it_creates_an_instance_of_offset_should_be_successful($startAt): void
    {
        $this->builder
            ->shouldReceive('offset')
            ->with($startAt)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            Builder::class,
            $this->factory->offset($startAt)
        );
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                0,
            ],
            [
                50,
            ],
            [
                100,
            ],
            [
                1000,
            ],
        ];
    }

    /**
     * @test
     */
    public function it_starts_at_position_0_should_be_successful(): void
    {
        $this->builder
            ->shouldReceive('offset')
            ->with(0)
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            Builder::class,
            $this->factory->offset()
        );
    }

    /**
     * @test
     * @dataProvider additionExceptionProvider
     */
    public function it_raises_an_exception_should_be_successful($params): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->offset($params);
    }

    /**
     * @return array[][]
     */
    public function additionExceptionProvider(): array
    {
        return [
            [
                'test',
            ],
            [
                'test1', 'test2',
            ],
            [
                '0', '1',
            ],
            [
                1.2,
            ],
            [
                1.2, 1.3,
            ],
            [
                [
                    1,
                ],
            ],
            [
                [
                    2,
                ],
            ],
            [
                '0',
            ],
            [
                '50',
            ],
            [
                '100',
            ],
            [
                '1000',
            ],
        ];
    }
}
