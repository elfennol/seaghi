<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application\UseCase;

use App\Battle\Application\Component\FormatName;
use App\Battle\Entity\Monster;
use App\Battle\Application\UseCase\ListMonster;
use App\Battle\Port\Out\FindAllEntityPort;
use App\Tests\Battle\Application\EntityIdSetterTrait;
use PHPUnit\Framework\TestCase;

class ListMonsterTest extends TestCase
{
    use EntityIdSetterTrait;

    private ListMonster $listMonster;
    private FindAllEntityPort $findAll;
    private FormatName $formatName;

    /**
     * When the player lists the monsters
     * Then the list result contains the ids and the full names of the monsters
     */
    public function testList(): void
    {
        $this->findAll->method('findAll')->willReturn([
            $this->buildMonster(1, 'my_first_name1', 'my_last_name1'),
            $this->buildMonster(2, 'my_first_name2', 'my_last_name2'),
        ]);

        $monsters = $this->listMonster->list();

        $this::assertEquals(1, $monsters[0]->getId());
        $this::assertEquals('my_first_name1 my_last_name1', $monsters[0]->getName());
        $this::assertEquals(2, $monsters[1]->getId());
        $this::assertEquals('my_first_name2 my_last_name2', $monsters[1]->getName());
    }

    protected function setUp(): void
    {
        $this->findAll = $this->createMock(FindAllEntityPort::class);
        $this->formatName = new FormatName();

        $this->listMonster = new ListMonster($this->findAll, $this->formatName);
    }

    private function buildMonster(int $id, string $firstName, string $lastName): Monster
    {
        $monster = new Monster();
        $monster->setFirstName($firstName);
        $monster->setLastName($lastName);
        $this->setEntityId($monster, $id);

        return $monster;
    }
}
