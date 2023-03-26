<?php

declare(strict_types=1);

namespace App\Shop\Port\Out\MessageContract;

/**
 * Message when a monster is sold.
 */
class MonsterSoldMessage implements MessageMc
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $categoryCode,
        public readonly int $level,
    ) {
    }
}
