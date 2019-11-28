<?php

namespace CodeHappy\DataLayer\Traits;

trait Aggregable
{
    /**
     * Count rows
     *
     * @return int
     */
    public function count(): int
    {
        return $this->builder()->count();
    }

    /**
     * Sum rows by column
     *
     * @param string $column
     * @return mixed
     */
    public function sum(string $column)
    {
        return $this->builder()->sum($column);
    }

    /**
     * Get maximum value from column
     *
     * @param string $column
     * @return mixed
     */
    public function max(string $column)
    {
        return $this->builder()->max($column);
    }

    /**
     * Get minimum value from column
     *
     * @param string $column
     * @return mixed
     */
    public function min(string $column)
    {
        return $this->builder()->min($column);
    }

    /**
     * Get average value from column
     *
     * @param string $column
     * @return mixed
     */
    public function avg(string $column)
    {
        return $this->builder()->avg($column);
    }
}
