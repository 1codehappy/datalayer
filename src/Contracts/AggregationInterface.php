<?php

namespace CodeHappy\DataLayer\Contracts;

interface AggregationInterface
{
    /**
     * Count rows
     *
     * @return int
     */
    public function count(): int;

    /**
     * Sum rows by column
     *
     * @param string $column
     * @return mixed
     */
    public function sum(string $column);

    /**
     * Get maximum value from column
     *
     * @param string $column
     * @return mixed
     */
    public function max(string $column);

    /**
     * Get minimum value from column
     *
     * @param string $column
     * @return mixed
     */
    public function min(string $column);

    /**
     * Get average value from column
     *
     * @param string $column
     * @return mixed
     */
    public function avg(string $column);
}
