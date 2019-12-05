<?php

namespace CodeHappy\DataLayer\Tests\Traits\Caching;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\Collection;
use CodeHappy\DataLayer\CachingRepository;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class AggregableTest extends TestCase
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * @var \CodeHappy\DataLayer\Repository
     */
    protected $repository;

    /**
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->connection   = Mockery::mock(Connection::class);
        $this->repository   = Mockery::mock(Repository::class);
        $this->cache        = Mockery::mock(Cache::class);
        $this->builder      = Mockery::mock(Builder::class);

        $this->cachingRepository = new class ($this->repository, $this->cache) extends CachingRepository
        {
            /**
             * @return $this
             */
            public function instance(): self
            {
                return $this;
            }

            /**
             * {@inheritDoc}
             */
            public function timeToLive(): int
            {
                return 1;
            }
        };
    }

    /**
     * @test
     */
    public function it_creates_an_instance_of_caching_repository(): void
    {
        $this->assertInstanceOf(CachingRepository::class, $this->cachingRepository->instance());
    }

    /**
     * @test
     */
    public function it_gets_the_time_to_live_should_be_successful(): void
    {
        $this->assertSame(1, $this->cachingRepository->timeToLive());
    }

    /**
     * @test
     */
    public function it_counts_the_number_of_rows_should_be_successful(): void
    {
        $this->repository
            ->shouldReceive('builder')
            ->twice()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('getConnection')
            ->once()
            ->andReturn($this->connection);

        $this->connection
            ->shouldReceive('getDatabaseName')
            ->once()
            ->andReturn('db_test');

        $this->builder
            ->shouldReceive('getBindings')
            ->once()
            ->andReturn([]);

        $this->builder
            ->shouldReceive('toSql')
            ->once()
            ->andReturn('SELECT COUNT(id) FROM users');

        $expected = 3;
        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn($expected);
        $actual = $this->cachingRepository->count();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function it_sums_the_value_from_column_should_be_successful(): void
    {
        $this->repository
            ->shouldReceive('builder')
            ->twice()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('getConnection')
            ->once()
            ->andReturn($this->connection);

        $this->connection
            ->shouldReceive('getDatabaseName')
            ->once()
            ->andReturn('db_test');

        $this->builder
            ->shouldReceive('getBindings')
            ->once()
            ->andReturn([]);

        $this->builder
            ->shouldReceive('toSql')
            ->once()
            ->andReturn('SELECT SUM(price) FROM products');

        $expected = 123.45;
        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn($expected);
        $actual = $this->cachingRepository->sum('price');

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function it_gets_the_max_value_from_column_should_be_successful(): void
    {
        $this->repository
            ->shouldReceive('builder')
            ->twice()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('getConnection')
            ->once()
            ->andReturn($this->connection);

        $this->connection
            ->shouldReceive('getDatabaseName')
            ->once()
            ->andReturn('db_test');

        $this->builder
            ->shouldReceive('getBindings')
            ->once()
            ->andReturn([]);

        $this->builder
            ->shouldReceive('toSql')
            ->once()
            ->andReturn('SELECT MAX(price) FROM products');

        $expected = 1234567890;
        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn($expected);
        $actual = $this->cachingRepository->max('pageviews');

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function it_gets_the_min_value_from_column_should_be_successful(): void
    {
        $this->repository
            ->shouldReceive('builder')
            ->twice()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('getConnection')
            ->once()
            ->andReturn($this->connection);

        $this->connection
            ->shouldReceive('getDatabaseName')
            ->once()
            ->andReturn('db_test');

        $this->builder
            ->shouldReceive('getBindings')
            ->once()
            ->andReturn([]);

        $this->builder
            ->shouldReceive('toSql')
            ->once()
            ->andReturn('SELECT MIN(birth_date) FROM users');

        $expected = '1978-09-17';
        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn($expected);
        $actual = $this->cachingRepository->min('birth_date');

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function it_gets_the_average_from_column_should_be_successful(): void
    {
        $this->repository
            ->shouldReceive('builder')
            ->twice()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('getConnection')
            ->once()
            ->andReturn($this->connection);

        $this->connection
            ->shouldReceive('getDatabaseName')
            ->once()
            ->andReturn('db_test');

        $this->builder
            ->shouldReceive('getBindings')
            ->once()
            ->andReturn([]);

        $this->builder
            ->shouldReceive('toSql')
            ->once()
            ->andReturn('SELECT AVG(age) FROM users');

        $expected = 22.5;
        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn($expected);

        $actual = $this->cachingRepository->avg('age');

        $this->assertSame($expected, $actual);
    }
}
