<?php

declare(strict_types=1);

namespace App\Shop\Port\Out;

use Symfony\Component\Uid\Uuid;

/**
 * Find an entity.
 */
interface FindEntityPort
{
    public function find(string $className, Uuid $id): object;
}
