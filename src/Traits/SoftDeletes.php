<?php

namespace CodeHappy\DataLayer\Traits;

use CodeHappy\DataLayer\Facades\QueryFactory;

trait SoftDeletes
{
    /**
     * Get "with trashed"
     *
     * @return mixed
     */
    public function withTrashed()
    {
        return $this->builder()
            ->withTrashed();
    }

    /**
     * Get "only trashed"
     *
     * @return mixed
     */
    public function onlyTrashed()
    {
        return $this->builder()
            ->onlyTrashed();
    }

    /**
     * Restore from trash (Soft Deletes)
     *
     * @return bool|null
     */
    public function restoreFromTrash(): ?bool
    {
        $result = $this->builder()->restore();
        if (
            $result === true &&
            method_exists($this, 'clearCache') === true
        ) {
            $this->clearCache();
        }
        return $result;
    }
}
