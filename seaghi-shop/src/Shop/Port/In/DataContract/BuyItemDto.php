<?php

declare(strict_types=1);

namespace App\Shop\Port\In\DataContract;

/**
 * Data given when you buy a monster.
 *
 * @see \App\Shop\Port\In\BuyItemPort
 */
class BuyItemDto
{
    public function __construct(
        public readonly int $id,
        public readonly bool $canBuy,
        public readonly string|null $msgCode,
    ) {
    }
}
