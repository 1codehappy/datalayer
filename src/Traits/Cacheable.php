<?php

namespace CodeHappy\DataLayer\Traits;

use Illuminate\Support\Facades\App;
use CodeHappy\DataLayer\CachingRepository;

trait Cacheable
{
    /**
     * Get instance of Cache Repository
     *
     * @return \CodeHappy\DataLayer\CachingRepository|null
     */
    public function cached(): ?CachingRepository
    {
        if (property_exists($this, 'cachingRepository') === true) {
            $this->newQuery();
            return App::getInstance()->make($this->cachingRepository);
        }

        return null;
    }
}
