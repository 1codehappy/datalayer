<?php

namespace CodeHappy\DataLayer\Traits;

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
        return $this->builder()
            ->toRawSql();
    }
}
