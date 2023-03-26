<?php

declare(strict_types=1);

namespace App\Shop\Application\Component;

use App\Shop\Entity\Monster;

/**
 * Check monster status.
 */
class CheckMonsterStatus
{
    /**
     * Check if a monster is ready to fight.
     *
     * A monster is ready to fight when this monster:
     *   - is available
     *   - not sick
     *   - has a level <= 10
     */
    public function isReadyToFight(Monster $monsterEntity): bool
    {
        return $monsterEntity->isAvailable() && !$monsterEntity->isSick() && $monsterEntity->getLevel() <= 10;
    }
}
