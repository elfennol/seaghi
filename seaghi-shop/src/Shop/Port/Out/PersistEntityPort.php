<?php

declare(strict_types=1);

namespace App\Shop\Port\Out;

/**
 * Persist an entity.
 */
interface PersistEntityPort
{
    public function persist(object $entity): void;
}
