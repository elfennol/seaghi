<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Application\UseCase\HealMonster;
use App\Battle\Infrastructure\HttpApi\Controller\HealMonster as HealMonsterController;
use App\Battle\Port\DataContract\HealMonsterDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class HealMonsterTest extends TestCase
{
    private HealMonster&MockObject $healMonster;
    private HealMonsterController $controller;

    protected function setUp(): void
    {
        $this->healMonster = $this->createMock(HealMonster::class);
        $this->controller = new HealMonsterController($this->healMonster);
    }

    public function testInvokeDelegatesToHealMonster(): void
    {
        $monsterId = Uuid::v7();
        $expectedDto = new HealMonsterDto($monsterId, 95, 15);

        $this->healMonster
            ->expects($this->once())
            ->method('heal')
            ->with($monsterId)
            ->willReturn($expectedDto);

        $actualDto = ($this->controller)($monsterId);

        $this::assertSame($expectedDto, $actualDto);
    }
}
