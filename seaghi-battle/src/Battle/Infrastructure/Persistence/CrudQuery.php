<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\Persistence;

use App\Battle\Port\Out\FindAllEntityPort;
use App\Battle\Port\Out\FindEntityPort;
use App\Battle\Port\Out\PersistEntityPort;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

/**
 * Generic CRUD queries.
 */
readonly class CrudQuery implements FindEntityPort, PersistEntityPort, FindAllEntityPort
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

    /**
     * @return iterable<object>
     */
    public function findAll(string $className): iterable
    {
        foreach ($this->entityManager->getRepository($className)->findAll() as $entity) {
            yield $entity;
        }
    }
}
