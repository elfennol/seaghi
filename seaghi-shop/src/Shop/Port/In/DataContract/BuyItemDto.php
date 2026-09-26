<?php

declare(strict_types=1);

namespace App\Shop\Port\In\DataContract;

use Symfony\Component\Uid\Uuid;

/**
 * Data given when you buy a monster.
 *
 * @see \App\Shop\Port\In\BuyItemPort
 */
readonly class BuyItemDto
{
    public function __construct(
        public Uuid $id,
        public bool $canBuy,
        public string|null $msgCode,
    ) {
    }
}
