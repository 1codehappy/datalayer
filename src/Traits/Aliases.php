<?php

namespace CodeHappy\DataLayer\Traits;

trait Aliases
{
    /**
     * Alias to newQuery()
     *
     * @return $this
     */
    public function clear(): self
    {
        return $this->newQuery();
    }
}
