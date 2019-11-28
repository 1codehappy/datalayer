<?php

namespace CodeHappy\DataLayer\Tests;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use CodeHappy\DataLayer\Repository;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class RepositoryTest extends TestCase
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

        $this->app = Mockery::mock(App::class);
        $this->app
            ->shouldReceive('make')
            ->with(Model::class)
            ->once()
            ->andReturn($this->model);

        $this->repository = new class($this->app) extends Repository
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
    }

    /**
     * @test
     */
    public function it_creates_an_instance_of_repository(): void
    {
        $this->assertInstanceOf(Repository::class, $this->repository->instance());
    }

    /**
     * @test
     */
    public function it_gets_model_class_should_be_successful(): void
    {
        $this->assertSame(Model::class, $this->repository->model());
    }

    /**
     * @test
     */
    public function it_fetches_the_instance_by_id_should_be_successful(): void
    {
        $this->builder
            ->shouldReceive('find')
            ->with(1234)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Model::class, $this->repository->fetchById(1234));
    }

    /**
     * @test
     */
    public function it_does_not_fetch_the_instance_by_id(): void
    {
        $this->builder
            ->shouldReceive('find')
            ->with(1234)
            ->once()
            ->andReturn(null);

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->assertNull($this->repository->fetchById(1234));
    }

    /**
     * @test
     */
    public function it_fetches_all_rows_should_be_successful(): void
    {
        $this->builder
            ->shouldReceive('get')
            ->once()
            ->andReturn(Mockery::mock(Collection::class));

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Collection::class, $this->repository->fetchAll());
    }

    /**
     * @test
     */
    public function it_fetches_a_collection_should_be_successful(): void
    {
        $this->builder
            ->shouldReceive('get')
            ->once()
            ->andReturn(Mockery::mock(Collection::class));

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Collection::class, $this->repository->fetch());
    }

    /**
     * @test
     */
    public function it_fetches_the_first_row_should_be_successful(): void
    {
        $this->builder
            ->shouldReceive('first')
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Model::class, $this->repository->fetchFirst());
    }

    /**
     * @test
     */
    public function it_does_not_fetch_the_first_row(): void
    {
        $this->builder
            ->shouldReceive('first')
            ->once()
            ->andReturn(null);

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->assertNull($this->repository->fetchFirst());
    }

    /**
     * @test
     */
    public function it_paginates_rows_should_be_successful(): void
    {
        $this->builder
            ->shouldReceive('paginate')
            ->once()
            ->andReturn(Mockery::mock(LengthAwarePaginator::class));

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(
            LengthAwarePaginator::class,
            $this->repository->paginate()
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

        $this->model
            ->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn($this->model);

        $this->assertInstanceOf(Model::class, $this->repository->create($data));
    }

    /**
     * @test
     */
    public function it_does_not_create_an_instance_of_model(): void
    {
        $data = [
            'email'     => 'test@test.com',
            'password'  => 'secret',
        ];

        $this->model
            ->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn(null);

        $this->assertNull($this->repository->create($data));
    }

    /**
     * @test
     */
    public function it_updates_data_from_object_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('find')
            ->with(1234)
            ->once()
            ->andReturn($this->model);

        $data = [
            'email'     => 'test1@test.com',
            'password'  => 'secret1',
        ];

        $this->model
            ->shouldReceive('fillable')
            ->with($data)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $this->assertInstanceOf(Model::class, $this->repository->update($data, 1234));
    }

    /**
     * @test
     */
    public function it_does_not_update_data_from_object_when_not_found(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('find')
            ->with(3456)
            ->once()
            ->andReturn(null);

        $this->assertNull($this->repository->update([
            'email'     => 'test1@test.com',
            'password'  => 'secret1',
        ], 3456));
    }

    /**
     * @test
     */
    public function it_updates_data_from_object_should_be_failful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('find')
            ->with(7890)
            ->once()
            ->andReturn($this->model);

        $data = [
            'email'     => 'test2@test.com',
            'password'  => 'secret2',
        ];

        $this->model
            ->shouldReceive('fillable')
            ->with($data)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->assertFalse($this->repository->update($data, 7890));
    }

    /**
     * @test
     */
    public function it_deletes_a_row_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('find')
            ->with(1234)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $this->assertSame(1, $this->repository->delete(1234));
    }

    /**
     * @test
     */
    public function it_deletes_a_row_should_be_failful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('find')
            ->with(1234)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('delete')
            ->once()
            ->andReturn(false);

        $this->assertSame(0, $this->repository->delete(1234));
    }

    /**
     * @test
     */
    public function it_deletes_a_collection_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('getKeyName')
            ->once()
            ->andReturn('id');

        $collection = Mockery::mock(Collection::class);
        $collection
            ->shouldReceive('pluck')
            ->with('id')
            ->once()
            ->andReturn($collection);
        $collection
            ->shouldReceive('all')
            ->once()
            ->andReturn([1234, 4567]);

        $this->model
            ->shouldReceive('newQuery')
            ->twice()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('find')
            ->with(1234)
            ->once()
            ->andReturn($this->model);

        $this->builder
            ->shouldReceive('find')
            ->with(4567)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('delete')
            ->twice()
            ->andReturn(true);

        $this->assertSame(2, $this->repository->delete($collection));
    }

    /**
     * @test
     */
    public function it_deletes_a_collection_should_be_failful(): void
    {
        $this->model
            ->shouldReceive('getKeyName')
            ->once()
            ->andReturn('id');

        $collection = Mockery::mock(Collection::class);
        $collection
            ->shouldReceive('pluck')
            ->with('id')
            ->once()
            ->andReturn($collection);
        $collection
            ->shouldReceive('all')
            ->once()
            ->andReturn([1234, 4567]);

        $this->model
            ->shouldReceive('newQuery')
            ->twice()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('find')
            ->with(1234)
            ->once()
            ->andReturn($this->model);

        $this->builder
            ->shouldReceive('find')
            ->with(4567)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('delete')
            ->twice()
            ->andReturn(false);

        $this->assertSame(0, $this->repository->delete($collection));
    }

    /**
     * @test
     */
    public function it_deletes_a_collection_except_one(): void
    {
        $this->model
            ->shouldReceive('getKeyName')
            ->once()
            ->andReturn('id');

        $collection = Mockery::mock(Collection::class);
        $collection
            ->shouldReceive('pluck')
            ->with('id')
            ->once()
            ->andReturn($collection);
        $collection
            ->shouldReceive('all')
            ->once()
            ->andReturn([1234, 4567]);

        $this->model
            ->shouldReceive('newQuery')
            ->twice()
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('find')
            ->with(1234)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $this->builder
            ->shouldReceive('find')
            ->with(4567)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('delete')
            ->once()
            ->andReturn(false);

        $this->assertSame(1, $this->repository->delete($collection));
    }

    /**
     * @test
     */
    public function it_deletes_an_array_of_ids_should_be_successful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->times(3)
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('find')
            ->with(1234)
            ->once()
            ->andReturn($this->model);

        $this->builder
            ->shouldReceive('find')
            ->with(4567)
            ->once()
            ->andReturn($this->model);

        $this->builder
            ->shouldReceive('find')
            ->with(7890)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('delete')
            ->times(3)
            ->andReturn(true);

        $this->assertSame(3, $this->repository->delete([1234, 4567, 7890]));
    }

    /**
     * @test
     */
    public function it_deletes_an_array_of_ids_should_be_failful(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->times(3)
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('find')
            ->with(1234)
            ->once()
            ->andReturn($this->model);

        $this->builder
            ->shouldReceive('find')
            ->with(4567)
            ->once()
            ->andReturn($this->model);

        $this->builder
            ->shouldReceive('find')
            ->with(7890)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('delete')
            ->times(3)
            ->andReturn(false);

        $this->assertSame(0, $this->repository->delete([1234, 4567, 7890]));
    }

    /**
     * @test
     */
    public function it_deletes_an_array_of_ids_except_one(): void
    {
        $this->model
            ->shouldReceive('newQuery')
            ->times(3)
            ->andReturn($this->builder);

        $this->builder
            ->shouldReceive('find')
            ->with(1234)
            ->once()
            ->andReturn($this->model);

        $this->builder
            ->shouldReceive('find')
            ->with(4567)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('delete')
            ->twice()
            ->andReturn(true);

        $this->builder
            ->shouldReceive('find')
            ->with(7890)
            ->once()
            ->andReturn($this->model);

        $this->model
            ->shouldReceive('delete')
            ->once()
            ->andReturn(false);

        $this->assertSame(2, $this->repository->delete([1234, 4567, 7890]));
    }

    /**
     * @test
     */
    public function it_gets_distinct_rows_should_be_successful(): void
    {
        $this->builder
            ->shouldReceive('distinct')
            ->once()
            ->andReturn(Mockery::mock(Collection::class));

        $this->model
            ->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Collection::class, $this->repository->distinct());
    }
}
