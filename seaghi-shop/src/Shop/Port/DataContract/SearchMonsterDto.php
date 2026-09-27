<?php

declare(strict_types=1);

namespace App\Shop\Port\DataContract;

use Symfony\Component\Uid\Uuid;

/**
 * Data needed to buy a monster.
 */
readonly class SearchMonsterDto
{
    public function __construct(
        public Uuid $id,
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
