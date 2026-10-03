<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\Persistence;

use App\Shop\Entity\Monster;
use App\Shop\Port\Out\Exception\MonsterNotFoundException;
use App\Shop\Port\Out\MonsterRepositoryPort;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * Doctrine implementation of MonsterRepositoryPort.
 *
 * The category is fetched with the monster, so the use cases never trigger lazy loading.
 */
readonly class DoctrineMonsterRepository implements MonsterRepositoryPort
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function get(Uuid $id): Monster
    {
        $monster = $this->find($id);
        if ($monster === null) {
            throw MonsterNotFoundException::forId($id);
        }

        return $monster;
    }

    public function find(Uuid $id): ?Monster
    {
        $dql = 'SELECT m, c
                FROM App\Shop\Entity\Monster m
                JOIN m.category c
                WHERE m.id = :id';

        /** @var Monster|null $monster */
        $monster = $this->entityManager->createQuery($dql)
            ->setParameter('id', $id, UuidType::NAME)
            ->getOneOrNullResult();

        return $monster;
    }

    public function save(Monster $monster): void
    {
        $this->entityManager->persist($monster);
    }
}
