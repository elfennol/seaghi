<?php

declare(strict_types=1);

namespace App\Shop\Port\DataContract;

use Symfony\Component\Uid\Uuid;

/**
 * Data given when you buy a monster.
 *
 * A purchase is either accepted (no message code) or rejected with a reason.
 */
readonly class BuyMonsterDto
{
    private function __construct(
        public Uuid $id,
        public bool $canBuy,
        public ?SaleRejectionReason $msgCode,
    ) {
    }

    public static function accepted(Uuid $id): self
    {
        return new self($id, true, null);
    }

    public static function rejected(Uuid $id, SaleRejectionReason $reason): self
    {
        return new self($id, false, $reason);
    }
}
