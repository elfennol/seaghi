<?php

declare(strict_types=1);

namespace App\Shop\Port\DataContract;

/**
 * Message when a monster is sold.
 */
readonly class MonsterSoldMessage
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $categoryCode,
        public int $level,
    ) {
    }
}
