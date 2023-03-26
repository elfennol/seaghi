<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\Persistence;

use App\Shop\Port\Out\FindEntityPort;
use App\Shop\Port\Out\PersistEntityPort;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

/**
 * Generic CRUD queries.
 */
class CrudQuery implements FindEntityPort, PersistEntityPort
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function persist(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function find(string $className, int $id): object
    {
        $entity = $this->entityManager->find($className, $id);
        if (null === $entity) {
            throw new RuntimeException('Entity does not exist');
        }

        return $entity;
    }
}
