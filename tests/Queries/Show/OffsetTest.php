<?php

namespace CodeHappy\DataLayer\Tests\Queries\Show;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Show\Offset as Query;
use CodeHappy\DataLayer\Tests\TestCase;
use InvalidArgumentException;
use Mockery;

class OffsetTest extends TestCase
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
     */
    public function it_handles_should_be_successful(): void
    {
        $this->builder
            ->shouldReceive('offset')
            ->with(1234)
            ->once()
            ->andReturn($this->builder);
        $this->assertInstanceOf(Builder::class, $this->query->handle(1234));
    }

    /**
     * @test
     */
    public function it_handles_without_params_should_be_successful(): void
    {
        $this->builder
            ->shouldReceive('offset')
            ->with(0)
            ->once()
            ->andReturn($this->builder);
        $this->assertInstanceOf(Builder::class, $this->query->handle());
    }
}
