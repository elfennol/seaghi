<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\Persistence;

use App\Battle\Entity\Monster;
use App\Battle\Infrastructure\Persistence\DoctrineMonsterRepository;
use App\Battle\Port\Out\Exception\MonsterNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
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
        $monster = new Monster('Fang', 'Dragon', 100, 15);
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
        $monster = new Monster('Fang', 'Dragon', 100, 15);
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

    public function testFindAllReturnsEntities(): void
    {
        $monster1 = new Monster('Fang', 'Dragon', 100, 15);
        $monster2 = new Monster('Claw', 'Beast', 80, 10);

        $entityRepository = $this->createStub(EntityRepository::class);
        $entityRepository->method('findAll')->willReturn([$monster1, $monster2]);

        $this->entityManager->method('getRepository')->willReturn($entityRepository);

        $results = iterator_to_array($this->repository->findAll());

        $this::assertSame([$monster1, $monster2], $results);
    }

    public function testSavePersistsEntity(): void
    {
        $monster = new Monster('Fang', 'Dragon', 100, 15);
        $mockEm = $this->createMock(EntityManagerInterface::class);
        $mockEm->expects($this->once())->method('persist')->with($monster);

        $repository = new DoctrineMonsterRepository($mockEm);
        $repository->save($monster);
    }
}
