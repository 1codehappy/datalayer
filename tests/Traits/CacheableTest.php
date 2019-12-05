<?php

namespace CodeHappy\DataLayer\Tests\Traits;

use Illuminate\Container\Container as App;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App as Facade;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use CodeHappy\DataLayer\Traits\Cacheable;
use CodeHappy\DataLayer\CachingRepository;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Tests\TestCase;
use BadMethodCallException;
use Mockery;

class CacheableTest extends TestCase
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * @var \CodeHappy\DataLayer\Repository
     */
    protected $repository;

    /**
     * @var \CodeHappy\DataLayer\CachingRepository
     */
    protected $cachingRepository;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->builder  = Mockery::mock(Builder::class);
        $this->model    = Mockery::mock(Model::class);
        $this->application = Mockery::mock(Application::class);
        $this->cachingRepository = Mockery::mock(CachingRepository::class);

        $this->app = Mockery::mock(App::class);
        $this->app
            ->shouldReceive('make')
            ->with(Model::class)
            ->twice()
            ->andReturn($this->model);

        $this->repository = new class ($this->app) extends Repository
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
            public function model(): string
            {
                return Model::class;
            }
        };

        $this->repositoryUsesCacheable = new class ($this->app) extends Repository
        {
            use Cacheable;

            public $cachingRepository;

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
            public function model(): string
            {
                return Model::class;
            }
        };

        $this->repositoryUsesCacheable->cachingRepository = Mockery::mock(CachingRepository::class);
    }

    /**
     * @test
     */
    public function it_raises_an_error_should_be_successful(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->assertNull($this->repository->cached());
    }

    /**
     * @test
     */
    public function it_calls_the_caching_repository_should_be_successful(): void
    {
        Facade::shouldReceive('getInstance')
            ->once()
            ->andReturn($this->application);

        $this->application
            ->shouldReceive('make')
            ->with(CachingRepository::class)
            ->once()
            ->andReturn($this->cachingRepository);

        $this->assertInstanceOf(
            CachingRepository::class,
            $this->repositoryUsesCacheable->cached()
        );
    }
}
