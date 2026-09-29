<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\Persistence;

use App\Battle\Port\DataContract\ListMonsterDto;
use App\Battle\Port\Out\ListMonsterPort;
use Doctrine\ORM\EntityManagerInterface;

readonly class ListMonsterQuery implements ListMonsterPort
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return iterable<ListMonsterDto>
     */
    public function list(): iterable
    {
        $dql = "SELECT NEW App\Battle\Port\DataContract\ListMonsterDto(
                    m.id,
                    CONCAT(m.firstName, ' ', m.lastName)
                )
                FROM App\Battle\Entity\Monster m";

        /** @var list<ListMonsterDto> $result */
        $result = $this->entityManager->createQuery($dql)->getResult();

        return $result;
    }
}
