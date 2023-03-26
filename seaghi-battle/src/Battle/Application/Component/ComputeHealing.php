<?php

declare(strict_types=1);

namespace App\Battle\Application\Component;

use App\Battle\Application\Component\Dice\Dice;
use App\Battle\Application\Component\Dice\RollDice;

/**
 * Compute the healing given to a monster.
 */
readonly class ComputeHealing
{
    public function __construct(
        private RollDice $rollDice,
    ) {
    }

    public function compute(): int
    {
        return $this->rollDice->roll(new Dice(8), 4);
    }
}
