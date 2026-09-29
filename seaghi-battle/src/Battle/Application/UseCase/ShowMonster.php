<?php

declare(strict_types=1);

namespace App\Battle\Application\UseCase;

use App\Battle\Port\DataContract\ShowEffectDto;
use App\Battle\Port\DataContract\ShowMonsterDto;
use App\Battle\Port\Out\MonsterRepositoryPort;
use Symfony\Component\Uid\Uuid;

readonly class ShowMonster
{
    public function __construct(
        private MonsterRepositoryPort $monsterRepository,
    ) {
    }

    public function show(Uuid $monsterId): ShowMonsterDto
    {
        $monster = $this->monsterRepository->get($monsterId);

        $effects = [];
        foreach ($monster->getEffects() as $effect) {
            $effects[] = new ShowEffectDto($effect->getCode());
        }

        return new ShowMonsterDto(
            $monster->getId(),
            $monster->getFullName(),
            $monster->getCurrentHealth(),
            $monster->getMaxHealth(),
            $monster->getDefense(),
            $effects,
        );
    }
}
