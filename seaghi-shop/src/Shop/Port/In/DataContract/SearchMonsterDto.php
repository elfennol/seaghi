<?php

declare(strict_types=1);

namespace App\Shop\Port\In\DataContract;

/**
 * An item of the result of the monster search.
 *
 * @see \App\Shop\Port\In\ListItemPort
 */
class SearchMonsterDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $categoryCode,
        public readonly int $level,
        public readonly int $price,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly bool $available,
        public readonly bool $sick,
    ) {
    }
}
