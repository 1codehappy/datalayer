<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface EagerLoadingInterface
{
    /**
     * Eager loading to relations
     *
     * @return mixed
     */
    public function with();
}
