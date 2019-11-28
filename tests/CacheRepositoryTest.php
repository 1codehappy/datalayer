<?php

namespace CodeHappy\DataLayer\Tests;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\Collection;
use CodeHappy\DataLayer\CacheRepository;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class CacheRepositoryTest extends TestCase
{
    /**
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->connection   = Mockery::mock(Connection::class);
        $this->repository   = Mockery::mock(Repository::class);
        $this->cache        = Mockery::mock(Cache::class);
        $this->model        = Mockery::mock(Model::class);
        $this->builder      = Mockery::mock(Builder::class);

        $this->cacheRepository = new class($this->repository, $this->cache) extends CacheRepository
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

        $this->cacheRepositoryWithTags = new class($this->repository, $this->cache) extends CacheRepository
        {
            /**
             * @var string|array
             */
            protected $tags = 'users';

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
    public function it_creates_an_instance_of_cache_repository(): void
    {
        $this->assertInstanceOf(CacheRepository::class, $this->cacheRepository->instance());
    }

    /**
     * @test
     */
    public function it_gets_the_time_to_live_should_be_successful(): void
    {
        $this->assertSame(1, $this->cacheRepository->timeToLive());
    }

    /**
     * @test
     */
    public function it_gets_the_cache_instance_without_tags(): void
    {
        $this->assertInstanceOf(Cache::class, $this->cacheRepository->cache());
    }

    /**
     * @test
     */
    public function it_gets_the_cache_instance_with_tags(): void
    {
        $this->cache
            ->shouldReceive('tags')
            ->with('users')
            ->once()
            ->andReturn($this->cache);

        $this->assertInstanceOf(Cache::class, $this->cacheRepositoryWithTags->cache());
    }

    /**
     * @test
     */
    public function it_fetches_the_instance_by_id_should_be_successful(): void
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
            ->shouldReceive('toRawSql')
            ->once()
            ->andReturn('SELECT * FROM users WHERE id = 1234');

        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn($this->model);

        $this->assertInstanceOf(Model::class, $this->cacheRepository->fetchById(1234));
    }

    /**
     * @test
     */
    public function it_fetches_all_rows_should_be_successful(): void
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
            ->shouldReceive('toRawSql')
            ->once()
            ->andReturn('SELECT * FROM users');

        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn(Mockery::mock(Collection::class));

        $this->assertInstanceOf(Collection::class, $this->cacheRepository->fetchAll());
    }

    /**
     * @test
     */
    public function it_fetches_a_collection_should_be_successful(): void
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
            ->shouldReceive('toRawSql')
            ->once()
            ->andReturn('SELECT * FROM users');

        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn(Mockery::mock(Collection::class));

        $this->assertInstanceOf(Collection::class, $this->cacheRepository->fetch());
    }

    /**
     * @test
     */
    public function it_fetches_the_first_row_should_be_successful(): void
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
            ->shouldReceive('toRawSql')
            ->once()
            ->andReturn('SELECT * FROM users LIMIT 1');

        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn(Mockery::mock(Model::class));

        $this->assertInstanceOf(Model::class, $this->cacheRepository->fetchFirst());
    }

    /**
     * @test
     */
    public function it_paginates_rows_should_be_successful(): void
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
            ->shouldReceive('toRawSql')
            ->once()
            ->andReturn('SELECT * FROM users LIMIT 50 OFFSET 0');

        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn(Mockery::mock(LengthAwarePaginator::class));

        $this->assertInstanceOf(
            LengthAwarePaginator::class,
            $this->cacheRepository->paginate()
        );
    }

    /**
     * @test
     */
    public function it_creates_an_instance_of_model(): void
    {
        $data = [
            'email'     => 'test@test.com',
            'password'  => 'secret',
        ];

        $this->repository
            ->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn($this->model);

        $this->cache
            ->shouldReceive('clear')
            ->once()
            ->andReturn(true);

        $this->assertInstanceOf(Model::class, $this->cacheRepository->create($data));
    }

    /**
     * @test
     */
    public function it_updates_data_from_object_should_be_successful(): void
    {
        $data = [
            'email'     => 'test1@test.com',
            'password'  => 'secret1',
        ];

        $this->repository
            ->shouldReceive('update')
            ->with($data, 1234)
            ->once()
            ->andReturn($this->model);

        $this->cache
            ->shouldReceive('clear')
            ->once()
            ->andReturn(true);

        $this->assertInstanceOf(Model::class, $this->cacheRepository->update($data, 1234));
    }

    /**
     * @test
     */
    public function it_deletes_a_row_should_be_successful(): void
    {
        $this->repository
            ->shouldReceive('delete')
            ->with(1234)
            ->once()
            ->andReturn(1);

        $this->cache
            ->shouldReceive('clear')
            ->once()
            ->andReturn(true);

        $this->assertSame(1, $this->cacheRepository->delete(1234));
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
            ->shouldReceive('toRawSql')
            ->once()
            ->andReturn('SELECT COUNT(id) FROM users');

        $expected = 3;
        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn($expected);

        $actual = $this->cacheRepository->count();

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
            ->shouldReceive('toRawSql')
            ->once()
            ->andReturn('SELECT SUM(price) FROM products');

        $expected = 123.45;
        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn($expected);

        $actual = $this->cacheRepository->sum('price');

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
            ->shouldReceive('toRawSql')
            ->once()
            ->andReturn('SELECT MAX(pageviews) FROM visits');

        $expected = 1234567890;
        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn($expected);

        $actual = $this->cacheRepository->max('pageviews');

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
            ->shouldReceive('toRawSql')
            ->once()
            ->andReturn('SELECT MIN(birth_date) FROM users');


        $expected = '1978-09-17';
        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn($expected);

        $actual = $this->cacheRepository->min('birth_date');

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
            ->shouldReceive('toRawSql')
            ->once()
            ->andReturn('SELECT AVG(age) FROM users');

        $expected = 22.5;
        $this->cache
            ->shouldReceive('remember')
            ->once()
            ->andReturn($expected);

        $actual = $this->cacheRepository->avg('age');

        $this->assertSame($expected, $actual);
    }
}
