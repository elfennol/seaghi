<?php

declare(strict_types=1);

namespace App\Battle\Application\Component\Dice;

use App\Battle\Port\Out\PickRandomIntPort;

/**
 * Roll a die to get a random number.
 */
readonly class RollDice
{
    public function __construct(
        private PickRandomIntPort $pickRandomInt
    ) {
    }

    /**
     * Roll a $dice.
     *
     * The result of this action is a number of one of the faces.
     * You may add a $modifier to add to the result.
     */
    public function roll(Dice $dice, int $modifier = 0): int
    {
        return $this->pickRandomInt->pickRandomInt(1, $dice->getNumberOfFaces()) + $modifier;
    }
}
