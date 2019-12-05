<?php

namespace CodeHappy\DataLayer\Tests\Queries\Joins;

use Illuminate\Database\Eloquent\Builder;
use CodeHappy\DataLayer\Contracts\RepositoryInterface;
use CodeHappy\DataLayer\Queries\Joins\RightJoin as Query;
use CodeHappy\DataLayer\Tests\TestCase;
use Closure;
use InvalidArgumentException;
use Mockery;

class RightJoinTest extends TestCase
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
        $this->builder      = Mockery::mock(Builder::class);
        $this->repository   = Mockery::mock(RepositoryInterface::class);
        $this->query        = new Query($this->builder, $this->repository);
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_handles_should_be_successful($args, $params): void
    {
        list($table, $relations) = $args;

        $join = is_callable($relations) ? $relations : function () use ($relations) {
            foreach ($relations as $primary => $foreign) {
                if (is_int($primary) === true || is_int($foreign) === true) {
                    return false;
                }
            }
            return true;
        };

        $this->builder
            ->shouldReceive('rightJoin')
            ->with($table, Mockery::on($join))
            ->once()
            ->andReturn($this->builder);

        $this->assertInstanceOf(Builder::class, $this->query->handle(...$params));
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                ['users', ['users.role_id' => 'roles.id']],
                ['users', 'users.role_id = roles.id'],
            ],
            [
                ['users', ['users.role_id' => 'roles.id']],
                ['users', ['users.role_id' => 'roles.id']],
            ],
            [
                ['users', ['users.role_id' => 'roles.id']],
                [
                    'users',
                    function ($join) {
                        $join->on('users.role_id', '=', 'roles.id');
                    },
                ],
            ],
            [
                [
                    'customers', [
                        'customers.id' => 'orders.customer_id',
                        'customers.role_id' => 'roles.id',
                    ],
                ],
                [
                    'customers',
                    'customers.id = orders.customer_id AND customers.role_id = roles.id',
                ],
            ],
            [
                [
                    'customers', [
                        'customers.id' => 'orders.customer_id',
                        'customers.role_id' => 'roles.id',
                    ],
                ],
                [
                    'customers', [
                        'customers.id' => 'orders.customer_id',
                        'customers.role_id' => 'roles.id',
                    ],
                ],
            ],
            [
                [
                    'customers', [
                        'customers.id' => 'orders.customer_id',
                        'customers.role_id' => 'roles.id',
                    ],
                ],
                [
                    'customers',
                    function ($join) {
                        $join->on('customers.id', '=', 'orders.customer_id')
                            ->on('customers.role_id', '=', 'roles.id');
                    },
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
                ['users'],
            ],
        ];
    }
}
