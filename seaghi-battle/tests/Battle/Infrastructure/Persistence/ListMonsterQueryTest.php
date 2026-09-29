<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\Persistence;

use App\Battle\Infrastructure\Persistence\ListMonsterQuery;
use App\Battle\Port\DataContract\ListMonsterDto;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ListMonsterQueryTest extends TestCase
{
    public function testListExecutesQueryAndReturnsDtos(): void
    {
        $dto = new ListMonsterDto(
            Uuid::v7(),
            'Fang Dragon',
        );

        $query = $this->createMock(Query::class);
        $query->expects($this->once())
            ->method('getResult')
            ->willReturn([$dto]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('createQuery')
            ->with($this->stringContains('SELECT NEW App\Battle\Port\DataContract\ListMonsterDto'))
            ->willReturn($query);

        $listMonsterQuery = new ListMonsterQuery($entityManager);
        $result = $listMonsterQuery->list();

        $this::assertSame([$dto], $result);
    }
}
