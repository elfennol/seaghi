<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\HttpApi\Controller;

use App\Shop\Application\UseCase\BuyItem;
use App\Shop\Infrastructure\HttpApi\Controller\BuyMonster;
use App\Shop\Port\DataContract\BuyItemDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class BuyMonsterTest extends TestCase
{
    private BuyItem&MockObject $buyItem;
    private BuyMonster $controller;

    protected function setUp(): void
    {
        $this->buyItem = $this->createMock(BuyItem::class);
        $this->controller = new BuyMonster($this->buyItem);
    }

    public function testInvokeDelegatesToBuyItem(): void
    {
        $monsterId = Uuid::v7();
        $expectedDto = new BuyItemDto($monsterId, true, null);

        $this->buyItem
            ->expects($this->once())
            ->method('buy')
            ->with($monsterId)
            ->willReturn($expectedDto);

        $actualDto = ($this->controller)($monsterId);

        $this::assertSame($expectedDto, $actualDto);
    }
}
