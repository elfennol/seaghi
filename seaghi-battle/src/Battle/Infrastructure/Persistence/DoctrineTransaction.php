<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\Persistence;

use App\Battle\Port\Out\TransactionPort;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineTransaction implements TransactionPort
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function run(callable $operation): mixed
    {
        return $this->entityManager->wrapInTransaction($operation);
    }
}
