<?php

declare(strict_types=1);

namespace App\Shop\Port\DataContract;

use Symfony\Component\Uid\Uuid;

/**
 * Data given when you buy a monster.
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
