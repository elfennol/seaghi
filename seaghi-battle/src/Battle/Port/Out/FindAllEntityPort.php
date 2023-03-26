<?php

declare(strict_types=1);

namespace App\Battle\Port\Out;

/**
 * Find all given an entity class name.
 */
interface FindAllEntityPort
{
    /**
     * @return iterable<object>
     */
    public function findAll(string $className): iterable;
}
