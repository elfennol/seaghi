<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Application\UseCase\HitMonster;
use App\Battle\Infrastructure\HttpApi\Controller\HitMonster as HitMonsterController;
use App\Battle\Port\DataContract\HitMonsterDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class HitMonsterTest extends TestCase
{
    private HitMonster&MockObject $hitMonster;
    private HitMonsterController $controller;

    protected function setUp(): void
    {
        $this->hitMonster = $this->createMock(HitMonster::class);
        $this->controller = new HitMonsterController($this->hitMonster);
    }

    public function testInvokeDelegatesToHitMonster(): void
    {
        $monsterId = Uuid::v7();
        $expectedDto = new HitMonsterDto($monsterId, 80, -20, []);

        $this->hitMonster
            ->expects($this->once())
            ->method('hit')
            ->with($monsterId)
            ->willReturn($expectedDto);

        $actualDto = ($this->controller)($monsterId);

        $this::assertSame($expectedDto, $actualDto);
    }
}
