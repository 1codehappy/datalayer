<?php

namespace CodeHappy\DataLayer\Facades;

use Illuminate\Support\Facades\Facade;
use CodeHappy\DataLayer\Queries\Factory;

class QueryFactory extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
