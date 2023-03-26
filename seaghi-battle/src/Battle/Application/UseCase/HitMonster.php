<?php

declare(strict_types=1);

namespace App\Battle\Application\UseCase;

use App\Battle\Application\Component\ComputeDamageSeverity;
use App\Battle\Application\Component\ComputeHitSeverity;
use App\Battle\Application\Component\ProcessHealth;
use App\Battle\Port\In\DataContract\HitMonsterDto;
use App\Battle\Entity\Monster;
use App\Battle\Port\In\HitMonsterPort;
use App\Battle\Port\Out\FindAllEffectIndexedPort;
use App\Battle\Port\Out\FindEntityPort;
use App\Battle\Port\Out\PersistEntityPort;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Hit a monster::
 *   - Roll a D20
 *   - if greater than the monster's defence then subtract the result from the monster's health
 *   - if 20 then the monster can not dodge and subtract twice the result from the monster's health
 *
 * Effects:
 *   - critical injury: when the result is 20
 *   - badass: when the monster dodges the attack (damage = 0)
 */
readonly class HitMonster implements HitMonsterPort
{
    public function __construct(
        private FindEntityPort $findEntity,
        private FindAllEffectIndexedPort $findAllEffectIndexed,
        private PersistEntityPort $persistEntity,
        private ComputeHitSeverity $computeHitSeverity,
        private ComputeDamageSeverity $computeDmgSeverity,
        private ProcessHealth $processHealth,
    ) {
    }

    public function hit(int $monsterId): HitMonsterDto
    {
        /** @var Monster $monster */
        $monster = $this->findEntity->find(Monster::class, $monsterId);

        $hitSeverity = $this->computeHitSeverity->compute();
        $damageSeverity = $this->computeDmgSeverity->compute($monster, $hitSeverity);
        $this->processHealth->injure($monster, $damageSeverity);

        $effectEntities = $this->findAllEffectIndexed->findAllIndexed();
        $effects = new ArrayCollection();
        foreach ($damageSeverity->getEffects() as $damageEffect) {
            if (isset($effectEntities[$damageEffect])) {
                $effects->add($effectEntities[$damageEffect]);
            }
        }
        $monster->setEffects($effects);

        $this->persistEntity->persist($monster);

        return new HitMonsterDto(
            $monster->getId(),
            $monster->getCurrentHealth(),
            -$damageSeverity->getAmount(),
            $damageSeverity->getEffects()
        );
    }
}
