<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application\UseCase;

use App\Battle\Application\UseCase\ListMonster;
use App\Battle\Port\DataContract\ListMonsterDto;
use App\Battle\Port\Out\ListMonsterPort;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ListMonsterTest extends TestCase
{
    private ListMonster $listMonster;
    private ListMonsterPort $listMonsterPort;

    protected function setUp(): void
    {
        $this->listMonsterPort = $this->createMock(ListMonsterPort::class);
        $this->listMonster = new ListMonster($this->listMonsterPort);
    }

    /**
     * When the player lists the monsters
     * Then the list result contains the ids and the full names of the monsters
     */
    public function testList(): void
    {
        $expected = [
            new ListMonsterDto(Uuid::fromString('11111111-1111-1111-1111-111111111111'), 'my_first_name1 my_last_name1'),
            new ListMonsterDto(Uuid::fromString('22222222-2222-2222-2222-222222222222'), 'my_first_name2 my_last_name2'),
        ];

        $this->listMonsterPort->expects($this->once())
            ->method('list')
            ->willReturn($expected);

        $monsters = $this->listMonster->list();

        $this::assertSame($expected, $monsters);
    }
}
