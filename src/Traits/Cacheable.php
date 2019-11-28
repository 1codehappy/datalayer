<?php

namespace CodeHappy\DataLayer\Traits;

use CodeHappy\DataLayer\Repository;

trait Cacheable
{
    /**
     * Get cache name
     *
     * @param \CodeHappy\DataLayer\Repository $repository
     * @return string
     */
    public function getCacheName(Repository $repository): string
    {
        $database = $repository
            ->builder()
            ->getConnection()
            ->getDatabaseName();

        $rawSql = $repository->builder()->toRawSql();

        return  md5($database . '|' . $rawSql);
    }
}
