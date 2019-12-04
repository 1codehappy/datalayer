<?php

namespace CodeHappy\DataLayer\Traits;

trait Massable
{
    /**
     * Update All
     *
     * @return int
     */
    public function updateAll(): int
    {
        return $this->builder()
            ->update();
    }

    /**
     * Delete All
     *
     * @return int
     */
    public function deleteAll(): int
    {
        return $this->builder()
            ->delete();
    }
}
