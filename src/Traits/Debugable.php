<?php

namespace CodeHappy\DataLayer\Traits;

use Illuminate\Support\Str;

/**
 * Optionally, you can add this trait into your repository class
 * to see the SQL's queries
 */
trait Debugable
{
    /**
     * Print SQL
     *
     * @return string
     */
    public function toSql(): string
    {
        $builder = $this->builder();

        $bindings = [];
        foreach ($builder->getBindings() as $binding) {
            $bindings[] = "'$binding'";
        }

        return Str::replaceArray('?', $bindings, $builder->toSql()) . ';';
    }
}
