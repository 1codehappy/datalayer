<?php

namespace CodeHappy\DataLayer\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Mockery;

class TestCase extends PHPUnitTestCase
{
    /**
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
    }
}
