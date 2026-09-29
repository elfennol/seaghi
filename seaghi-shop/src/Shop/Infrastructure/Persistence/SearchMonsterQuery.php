<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\Persistence;

use App\Shop\Port\DataContract\SearchMonsterDto;
use App\Shop\Port\Out\SearchMonsterPort;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Set of queries to search a monster.
 */
readonly class SearchMonsterQuery implements SearchMonsterPort
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function search(int $levelMin, int $levelMax): iterable
    {
        $dql = 'SELECT NEW App\Shop\Port\DataContract\SearchMonsterDto(
                    m.id,
                    c.code,
                    m.level,
                    m.price,
                    m.firstName,
                    m.lastName,
                    m.available,
                    m.sick
                )
                FROM App\Shop\Entity\Monster m
                JOIN m.category c
                WHERE m.level >= :levelMin AND m.level <= :levelMax';

        /** @var list<SearchMonsterDto> $result */
        $result = $this->entityManager->createQuery($dql)
            ->setParameter('levelMin', $levelMin)
            ->setParameter('levelMax', $levelMax)
            ->getResult();

        return $result;
    }
}
