<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\HttpApi\Controller;

use App\Shop\Application\UseCase\ListMonster;
use App\Shop\Infrastructure\HttpApi\Controller\ListMonster as ListMonsterController;
use App\Shop\Infrastructure\HttpApi\Dto\MonsterListRequest;
use App\Shop\Port\DataContract\SearchMonsterDto;
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

    /**
     * Given a valid monster list request
     * When the controller is invoked
     * Then it delegates to the use case with given levels and returns the monster list
     */
    public function testInvokeReturnsMonsters(): void
    {
        $request = new MonsterListRequest(levelMin: 1, levelMax: 5);
        $expectedMonsters = [
            new SearchMonsterDto(
                id: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                categoryCode: 'BEAST',
                level: 3,
                price: 100,
                firstName: 'Fire',
                lastName: 'Dragon',
                available: true,
                sick: false,
            ),
        ];

        $this->listMonster
            ->expects($this->once())
            ->method('execute')
            ->with(1, 5)
            ->willReturn($expectedMonsters);

        $actualMonsters = ($this->controller)($request);

        $this::assertSame($expectedMonsters, $actualMonsters);
    }

    /**
     * Given a monster list request matching no monsters
     * When the controller is invoked
     * Then it returns an empty list
     */
    public function testInvokeReturnsEmptyList(): void
    {
        $request = new MonsterListRequest(levelMin: 50, levelMax: 100);

        $this->listMonster
            ->expects($this->once())
            ->method('execute')
            ->with(50, 100)
            ->willReturn([]);

        $actualMonsters = ($this->controller)($request);

        $this::assertSame([], $actualMonsters);
    }

    /**
     * Given a monster list request with identical min and max levels
     * When the controller is invoked
     * Then it passes the exact levels to the use case
     */
    public function testInvokePassesLevelBounds(): void
    {
        $request = new MonsterListRequest(levelMin: 10, levelMax: 10);

        $this->listMonster
            ->expects($this->once())
            ->method('execute')
            ->with(10, 10)
            ->willReturn([]);

        ($this->controller)($request);
    }
}
