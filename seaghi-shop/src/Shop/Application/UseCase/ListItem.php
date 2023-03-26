<?php

declare(strict_types=1);

namespace App\Shop\Application\UseCase;

use App\Shop\Port\In\DataContract\SearchMonsterDto;
use App\Shop\Port\In\ListItemPort;
use App\Shop\Port\Out\SearchMonsterPort;

/**
 * The player is not allowed to see a monster with a level > 10 in the list.
 * In the future some rights may be defined to allow some player to see these monsters.
 *
 * The minimum level is 1. If not given then it is set to 1.
 * The maximum level is 10. If level > 10 given then it is set to 10.
 */
readonly class ListItem implements ListItemPort
{
    public function __construct(
        private SearchMonsterPort $searchMonster,
    ) {
    }

    public function list(int|null $levelMin, int|null $levelMax): iterable
    {
        $listResult = [];
        foreach (
            $this->searchMonster->search(
                $levelMin ?? 1,
                null === $levelMax || $levelMax > 10 ? 10 : $levelMax
            ) as $monsterEntity
        ) {
            $listResult[] = new SearchMonsterDto(
                $monsterEntity->getId(),
                $monsterEntity->getCategory()->getCode(),
                $monsterEntity->getLevel(),
                $monsterEntity->getPrice(),
                $monsterEntity->getFirstName(),
                $monsterEntity->getLastName(),
                $monsterEntity->isAvailable(),
                $monsterEntity->isSick(),
            );
        }

        return $listResult;
    }
}
