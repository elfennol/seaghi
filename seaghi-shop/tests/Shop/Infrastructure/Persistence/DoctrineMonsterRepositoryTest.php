<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\Persistence;

use App\Shop\Entity\Category;
use App\Shop\Entity\Monster;
use App\Shop\Infrastructure\Persistence\DoctrineMonsterRepository;
use App\Shop\Port\Out\Exception\MonsterNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class DoctrineMonsterRepositoryTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private DoctrineMonsterRepository $repository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createStub(EntityManagerInterface::class);
        $this->repository = new DoctrineMonsterRepository($this->entityManager);
    }

    public function testFindReturnsMonster(): void
    {
        $id = Uuid::v7();
        $monster = new Monster(new Category(), 2, 20, 'Fang', 'Beast');
        $this->entityManager->method('find')->willReturn($monster);

        $result = $this->repository->find($id);

        $this::assertSame($monster, $result);
    }

    public function testFindReturnsNullWhenNotFound(): void
    {
        $id = Uuid::v7();
        $this->entityManager->method('find')->willReturn(null);

        $result = $this->repository->find($id);

        $this::assertNull($result);
    }

    public function testGetReturnsMonster(): void
    {
        $id = Uuid::v7();
        $monster = new Monster(new Category(), 2, 20, 'Fang', 'Beast');
        $this->entityManager->method('find')->willReturn($monster);

        $result = $this->repository->get($id);

        $this::assertSame($monster, $result);
    }

    public function testGetThrowsWhenNotFound(): void
    {
        $id = Uuid::v7();
        $this->entityManager->method('find')->willReturn(null);

        $this->expectException(MonsterNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Monster with ID "%s" not found.', $id->toRfc4122()));

        $this->repository->get($id);
    }

    public function testSavePersistsEntity(): void
    {
        $monster = new Monster(new Category(), 2, 20, 'Fang', 'Beast');
        $mockEm = $this->createMock(EntityManagerInterface::class);
        $mockEm->expects($this->once())->method('persist')->with($monster);

        $repository = new DoctrineMonsterRepository($mockEm);
        $repository->save($monster);
    }
}
