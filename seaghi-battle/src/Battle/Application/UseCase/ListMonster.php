<?php

declare(strict_types=1);

namespace App\Battle\Application\UseCase;

use App\Battle\Port\DataContract\ListMonsterDto;
use App\Battle\Port\Out\MonsterRepositoryPort;

readonly class ListMonster
{
    public function __construct(
        private MonsterRepositoryPort $monsterRepository,
    ) {
    }

    /**
     * @return ListMonsterDto[]
     */
    public function list(): array
    {
        $monsters = [];
        foreach ($this->monsterRepository->findAll() as $monster) {
            assert($monster->getId() !== null);
            $monsters[] = new ListMonsterDto(
                $monster->getId(),
                $monster->getFullName(),
            );
        }

        return $monsters;
    }
}
