<?php

declare(strict_types=1);

namespace App\Battle\Port\Out;

/**
 * Pick a random int.
 */
interface PickRandomIntPort
{
    /**
     * Random int between $min and $max.
     */
    public function pickRandomInt(int $min, int $max): int;
}
