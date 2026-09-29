<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\Persistence;

use App\Battle\Entity\Monster;
use App\Battle\Port\Out\Exception\MonsterNotFoundException;
use App\Battle\Port\Out\MonsterRepositoryPort;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Doctrine implementation of MonsterRepositoryPort.
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
        return $this->entityManager->find(Monster::class, $id);
    }

    public function save(Monster $monster): void
    {
        $this->entityManager->persist($monster);
    }
}
