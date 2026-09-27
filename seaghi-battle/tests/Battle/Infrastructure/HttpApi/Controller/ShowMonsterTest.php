<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Application\UseCase\ShowMonster;
use App\Battle\Infrastructure\HttpApi\Controller\ShowMonster as ShowMonsterController;
use App\Battle\Port\DataContract\ShowMonsterDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ShowMonsterTest extends TestCase
{
    private ShowMonster&MockObject $showMonster;
    private ShowMonsterController $controller;

    protected function setUp(): void
    {
        $this->showMonster = $this->createMock(ShowMonster::class);
        $this->controller = new ShowMonsterController($this->showMonster);
    }

    public function testInvokeDelegatesToShowMonster(): void
    {
        $monsterId = Uuid::v7();
        $expectedDto = new ShowMonsterDto($monsterId, 'Fang Dragon', 80, 100, 15, []);

        $this->showMonster
            ->expects($this->once())
            ->method('show')
            ->with($monsterId)
            ->willReturn($expectedDto);

        $actualDto = ($this->controller)($monsterId);

        $this::assertSame($expectedDto, $actualDto);
    }
}
