<?php

declare(strict_types=1);

namespace App\Battle\Port\Out;

use Symfony\Component\Uid\Uuid;

/**
 * Find entity.
 */
interface FindEntityPort
{
    public function find(string $className, Uuid $id): object;
}
