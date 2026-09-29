<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\Persistence;

use App\Shop\Infrastructure\Persistence\IdentityGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;

class IdentityGeneratorTest extends TestCase
{
    public function testGenerateReturnsUuid(): void
    {
        $expectedUuid = Uuid::v7();
        $uuidFactory = $this->createMock(UuidFactory::class);
        $uuidFactory->expects($this->once())
            ->method('create')
            ->willReturn($expectedUuid);

        $identityGenerator = new IdentityGenerator($uuidFactory);

        $this::assertSame($expectedUuid, $identityGenerator->generate());
    }
}
