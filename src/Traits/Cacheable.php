<?php

namespace CodeHappy\DataLayer\Traits;

use CodeHappy\DataLayer\CacheRepository;

trait Cacheable
{
    /**
     * Get instance of Cache Repository
     *
     * @return \CodeHappy\DataLayer\CacheRepository|null
     */
    public function cached(): ?CacheRepository
    {
        if (method_exists($this, 'cacheRepository') === true) {
            return $this->cacheRepository;
        }

        return null;
    }
}
