<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\Random;

use App\Battle\Port\Out\PickRandomIntPort;

/**
 * Random number generator
 */
class RandomProcessor implements PickRandomIntPort
{
    /**
     * Random int between $min and $max.
     */
    public function pickRandomInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
