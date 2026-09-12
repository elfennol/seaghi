<?php

declare(strict_types=1);

namespace App\Shop\Port\In\DataContract;

/**
 * An item of the result of the monster search.
 *
 * @see \App\Shop\Port\In\ListItemPort
 */
readonly class SearchMonsterDto
{
    public function __construct(
        public int $id,
        public string $categoryCode,
        public int $level,
        public int $price,
        public string $firstName,
        public string $lastName,
        public bool $available,
        public bool $sick,
    ) {
    }
}
