<?php

declare(strict_types=1);

namespace App\Shop\Port\Out;

/**
 * Withdraw from the account of the player.
 */
interface WithdrawFromAccountPort
{
    /**
     * True if withdraw is ok, false otherwise.
     */
    public function withdraw(): bool;
}
