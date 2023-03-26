<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\Persistence;

use App\Shop\Entity\Monster;
use App\Shop\Port\Out\SearchMonsterPort;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Set of queries to search a monster.
 */
class SearchMonsterQuery implements SearchMonsterPort
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function search(int $levelMin, int $levelMax): iterable
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where($expr->gte('level', $levelMin));
        $criteria->andwhere($expr->lte('level', $levelMax));

        /** @var Monster $monsterEntity */
        foreach ($this->entityManager->getRepository(Monster::class)->matching($criteria) as $monsterEntity) {
            yield $monsterEntity;
        }
    }
}
