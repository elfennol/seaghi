<?php

declare(strict_types=1);

namespace App\Shop\Port\Out\MessageContract;

/**
 * Message when a monster is sold.
 */
readonly class MonsterSoldMessage implements MessageMc
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $categoryCode,
        public int $level,
    ) {
    }
}
