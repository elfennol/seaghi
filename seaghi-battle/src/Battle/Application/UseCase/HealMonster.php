<?php

declare(strict_types=1);

namespace App\Battle\Application\UseCase;

use App\Battle\Application\Component\ComputeHealing;
use App\Battle\Application\Component\ProcessHealth;
use App\Battle\Port\In\DataContract\HealMonsterDto;
use App\Battle\Entity\Monster;
use App\Battle\Port\In\HealMonsterPort;
use App\Battle\Port\Out\FindEntityPort;
use App\Battle\Port\Out\PersistEntityPort;

/**
 * Heal a monster:
 *   - Roll a d8
 *   - Add 4 to the dice
 *   - Add the result to the monster's health
 *
 * The monster's current health does not exceed its maximum health.
 */
readonly class HealMonster implements HealMonsterPort
{
    public function __construct(
        private FindEntityPort $findEntity,
        private PersistEntityPort $persistEntity,
        private ComputeHealing $computeHeal,
        private ProcessHealth $processHealth,
    ) {
    }

    /**
     * Heal the id $monsterId monster.
     */
    public function heal(int $monsterId): HealMonsterDto
    {
        /** @var Monster $monster */
        $monster = $this->findEntity->find(Monster::class, $monsterId);

        $healResult = $this->computeHeal->compute();
        $this->processHealth->heal($monster, $healResult);

        $this->persistEntity->persist($monster);

        return new HealMonsterDto(
            $monster->getId(),
            $monster->getCurrentHealth(),
            $healResult
        );
    }
}
