<?php

declare(strict_types=1);

namespace App\Battle\Application\UseCase;

use App\Battle\Application\Component\FormatName;
use App\Battle\Port\In\DataContract\ListMonsterDto;
use App\Battle\Entity\Monster;
use App\Battle\Port\In\ListMonsterPort;
use App\Battle\Port\Out\FindAllEntityPort;

readonly class ListMonster implements ListMonsterPort
{
    public function __construct(
        private FindAllEntityPort $findAll,
        private FormatName $formatName,
    ) {
    }

    public function list(): array
    {
        $monsters = [];
        /** @var Monster $monster */
        foreach ($this->findAll->findAll(Monster::class) as $monster) {
            $monsters[] = new ListMonsterDto(
                $monster->getId(),
                $this->formatName->getFullName($monster),
            );
        }

        return $monsters;
    }
}
