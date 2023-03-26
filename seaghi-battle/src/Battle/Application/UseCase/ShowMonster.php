<?php

declare(strict_types=1);

namespace App\Battle\Application\UseCase;

use App\Battle\Application\Component\FormatName;
use App\Battle\Port\In\DataContract\ShowEffectDto;
use App\Battle\Port\In\DataContract\ShowMonsterDto;
use App\Battle\Entity\Monster;
use App\Battle\Port\In\ShowMonsterPort;
use App\Battle\Port\Out\FindEntityPort;

readonly class ShowMonster implements ShowMonsterPort
{
    public function __construct(
        private FindEntityPort $findEntity,
        private FormatName $formatName,
    ) {
    }

    public function show(int $monsterId): ShowMonsterDto
    {
        /** @var Monster $monster */
        $monster = $this->findEntity->find(Monster::class, $monsterId);

        $effects = [];
        foreach ($monster->getEffects() as $effect) {
            $effects[] = new ShowEffectDto($effect->getCode());
        }

        return new ShowMonsterDto(
            $monster->getId(),
            $this->formatName->getFullName($monster),
            $monster->getCurrentHealth(),
            $monster->getMaxHealth(),
            $monster->getDefense(),
            $effects,
        );
    }
}
