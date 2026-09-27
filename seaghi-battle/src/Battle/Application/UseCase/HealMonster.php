<?php

declare(strict_types=1);

namespace App\Battle\Application\UseCase;

use App\Battle\Application\Component\ComputeHealing;
use App\Battle\Port\DataContract\HealMonsterDto;
use App\Battle\Port\Out\MonsterRepositoryPort;
use App\Battle\Port\Out\TransactionPort;
use Symfony\Component\Uid\Uuid;

readonly class HealMonster
{
    public function __construct(
        private MonsterRepositoryPort $monsterRepository,
        private TransactionPort $transaction,
        private ComputeHealing $computeHeal,
    ) {
    }

    public function heal(Uuid $monsterId): HealMonsterDto
    {
        $monster = $this->monsterRepository->get($monsterId);
        assert($monster->getId() !== null);

        $healResult = $this->computeHeal->compute();

        $this->transaction->run(function () use ($monster, $healResult): void {
            $monster->heal($healResult);
            $this->monsterRepository->save($monster);
        });

        return new HealMonsterDto(
            $monsterId,
            $monster->getCurrentHealth(),
            $healResult,
        );
    }
}
