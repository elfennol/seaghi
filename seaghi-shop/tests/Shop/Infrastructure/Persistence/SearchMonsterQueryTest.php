<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\Persistence;

use App\Shop\Infrastructure\Persistence\SearchMonsterQuery;
use App\Shop\Port\DataContract\SearchMonsterDto;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class SearchMonsterQueryTest extends TestCase
{
    public function testSearchExecutesQueryAndReturnsDtos(): void
    {
        $dto = new SearchMonsterDto(
            Uuid::v7(),
            'lol_cat',
            3,
            75,
            'Felix',
            'Cat',
            true,
            false,
        );

        $query = $this->createMock(Query::class);
        $query->expects($this->exactly(2))
            ->method('setParameter')
            ->willReturnCallback(fn (string $key, mixed $value): mixed => $query);
        $query->expects($this->once())
            ->method('getResult')
            ->willReturn([$dto]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('createQuery')
            ->with($this->stringContains('SELECT NEW App\Shop\Port\DataContract\SearchMonsterDto'))
            ->willReturn($query);

        $searchMonsterQuery = new SearchMonsterQuery($entityManager);
        $result = $searchMonsterQuery->search(1, 10);

        $this::assertSame([$dto], $result);
    }
}
