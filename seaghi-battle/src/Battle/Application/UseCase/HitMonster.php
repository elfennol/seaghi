<?php

declare(strict_types=1);

namespace App\Battle\Application\UseCase;

use App\Battle\Application\Rule\ComputeDamageSeverity;
use App\Battle\Application\Rule\ComputeHitSeverity;
use App\Battle\Application\Rule\HitSeverity;
use App\Battle\Port\DataContract\HitMonsterDto;
use App\Battle\Port\Out\EffectRepositoryPort;
use App\Battle\Port\Out\MonsterRepositoryPort;
use App\Battle\Port\Out\TransactionPort;
use Symfony\Component\Uid\Uuid;

readonly class HitMonster
{
    public function __construct(
        private MonsterRepositoryPort $monsterRepository,
        private EffectRepositoryPort $effectRepository,
        private TransactionPort $transaction,
        private ComputeHitSeverity $computeHitSeverity,
        private ComputeDamageSeverity $computeDmgSeverity,
    ) {
    }

    public function hit(Uuid $monsterId): HitMonsterDto
    {
        $monster = $this->monsterRepository->get($monsterId);

        $hitSeverity = $this->computeHitSeverity->compute();
        $damageSeverity = $this->computeDmgSeverity->compute($monster, $hitSeverity);

        $effectEntities = $this->effectRepository->findAllIndexed();
        $appliedEffects = [];
        foreach ($damageSeverity->effects as $damageEffect) {
            if (isset($effectEntities[$damageEffect])) {
                $appliedEffects[] = $effectEntities[$damageEffect];
            }
        }

        $this->transaction->run(function () use ($monster, $damageSeverity, $appliedEffects): void {
            $monster->applyDamage($damageSeverity->amount);
            $monster->replaceEffects($appliedEffects);
            $this->monsterRepository->save($monster);
        });

        return new HitMonsterDto(
            $monster->getId(),
            $monster->getCurrentHealth(),
            -$damageSeverity->amount,
            $damageSeverity->effects,
        );
    }
}
