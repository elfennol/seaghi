<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\Persistence;

use App\Battle\Infrastructure\Persistence\DoctrineTransaction;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class DoctrineTransactionTest extends TestCase
{
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
