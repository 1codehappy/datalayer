<?php

namespace CodeHappy\DataLayer\Traits\Caching;

trait Massable
{
    /**
     * Update All
     *
     * @param array $data
     * @return int
     */
    public function updateAll(array $data): int
    {
        $updates = $this->builder()
            ->update($data);
        if ($updates > 0) {
            $this->clearCache();
        }
        return $updates;
    }

    /**
     * Delete All
     *
     * @return int
     */
    public function deleteAll(): int
    {
        $deletes = $this->builder()
            ->delete();
        if ($deletes > 0) {
            $this->clearCache();
        }
        return $deletes;
    }
}
