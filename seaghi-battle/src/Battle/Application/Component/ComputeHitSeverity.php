<?php

declare(strict_types=1);

namespace App\Battle\Application\Component;

use App\Battle\Application\Component\Dice\Dice;
use App\Battle\Application\Component\Dice\RollDice;
use App\Battle\Application\Enum\HitForce;

/**
 * Hit severity strategy for a monster.
 */
class ComputeHitSeverity
{
    public function __construct(
        private RollDice $rollDice,
    ) {
    }

    /**
     * Compute hit severity.
     */
    public function compute(): HitSeverity
    {
        $rollResult = $this->rollDice->roll(new Dice(20));

        $injuryType = HitForce::NORMAL;
        if (20 === $rollResult) {
            $injuryType = HitForce::CRITICAL;
        }

        return new HitSeverity($rollResult, $injuryType);
    }
}
