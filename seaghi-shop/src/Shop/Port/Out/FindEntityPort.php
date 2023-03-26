<?php

declare(strict_types=1);

namespace App\Shop\Port\Out;

/**
 * Find an entity.
 */
interface FindEntityPort
{
    public function find(string $className, int $id): object;
}
