<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Application\UseCase\ListMonster;
use App\Battle\Infrastructure\HttpApi\Controller\ListMonster as ListMonsterController;
use App\Battle\Port\DataContract\ListMonsterDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ListMonsterTest extends TestCase
{
    private ListMonster&MockObject $listMonster;
    private ListMonsterController $controller;

    protected function setUp(): void
    {
        $this->listMonster = $this->createMock(ListMonster::class);
        $this->controller = new ListMonsterController($this->listMonster);
    }

    public function testInvokeDelegatesToListMonster(): void
    {
        $expected = [
            new ListMonsterDto(Uuid::v7(), 'Fang Dragon'),
        ];

        $this->listMonster
            ->expects($this->once())
            ->method('list')
            ->willReturn($expected);

        $actual = ($this->controller)();

        $this::assertSame($expected, $actual);
    }
}
