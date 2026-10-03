<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\Persistence;

use App\Shop\Entity\Category;
use App\Shop\Entity\Monster;
use App\Shop\Infrastructure\Persistence\DoctrineMonsterRepository;
use App\Shop\Port\Out\Exception\MonsterNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

class DoctrineMonsterRepositoryTest extends TestCase
{
    public function testFindFetchesCategoryWithMonster(): void
    {
        $id = Uuid::v7();
        $monster = new Monster($id, new Category(), 2, 20, 'Fang', 'Beast');

        $query = $this->createMock(Query::class);
        $query->expects($this->once())
            ->method('setParameter')
            ->with('id', $id, UuidType::NAME)
            ->willReturnSelf();
        $query->method('getOneOrNullResult')->willReturn($monster);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('createQuery')
            ->with($this->logicalAnd(
                $this->stringContains('SELECT m, c'),
                $this->stringContains('JOIN m.category c'),
            ))
            ->willReturn($query);

        $result = new DoctrineMonsterRepository($entityManager)->find($id);

        $this::assertSame($monster, $result);
    }

    public function testFindReturnsNullWhenNotFound(): void
    {
        $result = $this->buildRepository(null)->find(Uuid::v7());

        $this::assertNull($result);
    }

    public function testGetReturnsMonster(): void
    {
        $id = Uuid::v7();
        $monster = new Monster($id, new Category(), 2, 20, 'Fang', 'Beast');

        $result = $this->buildRepository($monster)->get($id);

        $this::assertSame($monster, $result);
    }

    public function testGetThrowsWhenNotFound(): void
    {
        $id = Uuid::v7();

        $this->expectException(MonsterNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Monster with ID "%s" not found.', $id->toRfc4122()));

        $this->buildRepository(null)->get($id);
    }

    public function testSavePersistsEntity(): void
    {
        $monster = new Monster(Uuid::v7(), new Category(), 2, 20, 'Fang', 'Beast');
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist')->with($monster);

        new DoctrineMonsterRepository($entityManager)->save($monster);
    }

    private function buildRepository(?Monster $queryResult): DoctrineMonsterRepository
    {
        $query = $this->createStub(Query::class);
        $query->method('setParameter')->willReturnSelf();
        $query->method('getOneOrNullResult')->willReturn($queryResult);

        $entityManager = $this->createStub(EntityManagerInterface::class);
        $entityManager->method('createQuery')->willReturn($query);

        return new DoctrineMonsterRepository($entityManager);
    }
}
