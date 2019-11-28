<?php

namespace CodeHappy\DataLayer\Tests;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\Collection;
use CodeHappy\DataLayer\Helpers\Sketchy;
use CodeHappy\DataLayer\Tests\TestCase;
use Mockery;

class SketchyTest extends TestCase
{
    /**
     * @var \CodeHappy\DataLayer\Helpers\Sketchy
     */
    protected $sketchy;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->sketchy = new Sketchy();
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_compiles_the_stub_should_be_successful(): void
    {
        $this->assertTrue(true);
    }

    /**
     * @return array[][]
     */
    public function additionProvider(): array
    {
        return [
            [
                //code
            ],
        ];
    }
}
