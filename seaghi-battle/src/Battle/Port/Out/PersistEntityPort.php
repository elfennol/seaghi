<?php

declare(strict_types=1);

namespace App\Battle\Port\Out;

/**
 * Persist entity.
 */
interface PersistEntityPort
{
    public function persist(object $entity): void;
}
