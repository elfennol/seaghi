<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\Persistence;

use App\Battle\Entity\Effect;
use App\Battle\Port\Out\FindAllEffectIndexedPort;
use Doctrine\ORM\EntityManagerInterface;

readonly class EffectCrudQuery implements FindAllEffectIndexedPort
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<string, Effect> Array key is the code of Effect
     */
    public function findAllIndexed(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('e')
            ->from(Effect::class, 'e', 'e.code')
            ->getQuery()
            ->getResult();
    }
}
