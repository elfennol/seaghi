<?php

declare(strict_types=1);

namespace App\Shop\Port\In\DataContract;

/**
 * Data given when you buy a monster.
 *
 * @see \App\Shop\Port\In\BuyItemPort
 */
readonly class BuyItemDto
{
    public function __construct(
        public int $id,
        public bool $canBuy,
        public string|null $msgCode,
    ) {
    }
}
