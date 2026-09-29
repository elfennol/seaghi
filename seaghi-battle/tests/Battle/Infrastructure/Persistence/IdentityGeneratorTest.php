<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\Persistence;

use App\Battle\Infrastructure\Persistence\IdentityGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;

class IdentityGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $uuid = Uuid::v7();
        $factory = $this->createMock(UuidFactory::class);
        $factory->expects($this->once())->method('create')->willReturn($uuid);

        $generator = new IdentityGenerator($factory);

        $this::assertSame($uuid, $generator->generate());
    }
}
