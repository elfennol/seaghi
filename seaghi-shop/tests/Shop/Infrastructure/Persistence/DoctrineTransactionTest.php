<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\Persistence;

use App\Shop\Infrastructure\Persistence\DoctrineTransaction;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class DoctrineTransactionTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private DoctrineTransaction $transaction;

    protected function setUp(): void
    {
        $this->entityManager = $this->createStub(EntityManagerInterface::class);
        $this->transaction = new DoctrineTransaction($this->entityManager);
    }

    public function testRunExecutesInTransaction(): void
    {
        $mockEm = $this->createMock(EntityManagerInterface::class);
        $mockEm->expects($this->once())
            ->method('wrapInTransaction')
            ->willReturnCallback(static fn (callable $operation): mixed => $operation());

        $transaction = new DoctrineTransaction($mockEm);
        $result = $transaction->run(static fn (): string => 'success');

        $this::assertSame('success', $result);
    }
}
