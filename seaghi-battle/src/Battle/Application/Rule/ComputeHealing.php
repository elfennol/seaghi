<?php

declare(strict_types=1);

namespace App\Battle\Application\Rule;

use App\Battle\Application\Rule\Dice\Dice;
use App\Battle\Application\Rule\Dice\RollDice;

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
        return $this->rollDice->roll(Dice::create(8), 4);
    }
}
