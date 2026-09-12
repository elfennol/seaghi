<?php

declare(strict_types=1);

namespace App\Battle\Application\Component;

use App\Battle\Entity\Monster;

/**
 * Process the health during the battle.
 */
class ProcessHealth
{
    public function injure(Monster $monster, DamageSeverity $damageSeverity): void
    {
        $monster->setCurrentHealth(
            $monster->getCurrentHealth() - $damageSeverity->amount < 0
                ? 0
                : $monster->getCurrentHealth() - $damageSeverity->amount
        );
    }

    public function heal(Monster $monster, int $healingPower): void
    {
        $monster->setCurrentHealth(
            $monster->getCurrentHealth() + $healingPower > $monster->getMaxHealth()
            ? $monster->getMaxHealth()
            : $monster->getCurrentHealth() + $healingPower
        );
    }
}
