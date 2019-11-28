<?php

namespace CodeHappy\DataLayer\Tests\Traits;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use CodeHappy\DataLayer\Traits\Cacheable;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class CacheableTest extends TestCase
{
    use Cacheable;

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
        $this->model        = Mockery::mock(Model::class);
        $this->builder      = Mockery::mock(Builder::class);
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_gets_the_cache_name_should_be_successful($database, $sql): void
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
            ->andReturn($database);

        $this->builder
            ->shouldReceive('toRawSql')
            ->once()
            ->andReturn($sql);

        $expected = md5($database . '|' . $sql);
        $actual = $this->getCacheName($this->repository);

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                'test1',
                'SELECT * FROM users WHERE id = 123',
            ],
            [
                'test2',
                'SELECT SUM(price) FROM products WHERE name LIKE \'a%\'',
            ],
        ];
    }
}
