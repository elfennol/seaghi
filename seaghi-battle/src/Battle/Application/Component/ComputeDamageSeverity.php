<?php

declare(strict_types=1);

namespace App\Battle\Application\Component;

use App\Battle\Application\Enum\HitForce;
use App\Battle\Entity\Effect;
use App\Battle\Entity\Monster;

/**
 * Damage severity strategy for a monster.
 */
class ComputeDamageSeverity
{
    /**
     *  Compute the final injury value.
     */
    public function compute(Monster $monster, HitSeverity $hitSeverity): DamageSeverity
    {
        $injuryResult = 0;
        $effects = [];
        if (
            HitForce::NORMAL === $hitSeverity->getHitForce() &&
            $hitSeverity->getRollResult() > $monster->getDefense()
        ) {
            $injuryResult = $hitSeverity->getRollResult();
        }

        if (HitForce::CRITICAL === $hitSeverity->getHitForce()) {
            $injuryResult = 2 * $hitSeverity->getRollResult();
            $effects[] = Effect::CODE_SERIOUS_INJURY;
        }

        if (0 === $injuryResult) {
            $effects[] = Effect::CODE_BADASS;
        }

        return new DamageSeverity($injuryResult, $effects);
    }
}
