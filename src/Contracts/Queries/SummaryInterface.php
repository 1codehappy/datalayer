<?php

namespace CodeHappy\DataLayer\Contracts\Queries;

interface SummaryInterface
{
    /**
     * Group by columns
     *
     * @return mixed
     */
    public function groupBy();

    /**
     * Having
     *
     * @return mixed
     */
    public function having();
}
