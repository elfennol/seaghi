<?php

declare(strict_types=1);

namespace App\Battle\Port\Out;

/**
 * Find entity.
 */
interface FindEntityPort
{
    public function find(string $className, int $id): object;
}
