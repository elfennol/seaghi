<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\Persistence;

use App\Battle\Entity\Effect;
use App\Battle\Infrastructure\Persistence\DoctrineEffectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class DoctrineEffectRepositoryTest extends TestCase
{
    public function testFindAllIndexedReturnsEffects(): void
    {
        $effect = new Effect();
        $effect->setCode(Effect::CODE_BADASS);
        $expected = [Effect::CODE_BADASS => $effect];

        $query = $this->createStub(Query::class);
        $query->method('getResult')->willReturn($expected);

        $queryBuilder = $this->createStub(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('getQuery')->willReturn($query);

        $entityManager = $this->createStub(EntityManagerInterface::class);
        $entityManager->method('createQueryBuilder')->willReturn($queryBuilder);

        $repository = new DoctrineEffectRepository($entityManager);
        $result = $repository->findAllIndexed();

        $this::assertSame($expected, $result);
    }
}
