<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\HttpApi\Controller;

use App\Shop\Application\UseCase\BuyMonster;
use App\Shop\Infrastructure\HttpApi\Controller\BuyMonster as BuyMonsterController;
use App\Shop\Port\DataContract\BuyMonsterDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class BuyMonsterTest extends TestCase
{
    private BuyMonster&MockObject $buyMonster;
    private BuyMonsterController $controller;

    protected function setUp(): void
    {
        $this->buyMonster = $this->createMock(BuyMonster::class);
        $this->controller = new BuyMonsterController($this->buyMonster);
    }

    public function testInvokeDelegatesToBuyMonster(): void
    {
        $monsterId = Uuid::v7();
        $expectedDto = BuyMonsterDto::accepted($monsterId);

        $this->buyMonster
            ->expects($this->once())
            ->method('execute')
            ->with($monsterId)
            ->willReturn($expectedDto);

        $actualDto = ($this->controller)($monsterId);

        $this::assertSame($expectedDto, $actualDto);
    }
}
