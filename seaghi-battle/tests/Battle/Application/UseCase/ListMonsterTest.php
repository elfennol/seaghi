<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application\UseCase;

use App\Battle\Application\Component\FormatName;
use App\Battle\Entity\Monster;
use App\Battle\Application\UseCase\ListMonster;
use App\Battle\Port\Out\FindAllEntityPort;
use App\Tests\Battle\Application\EntityIdSetterTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ListMonsterTest extends TestCase
{
    use EntityIdSetterTrait;

    private ListMonster $listMonster;
    private FindAllEntityPort $findAll;
    private FormatName $formatName;

    protected function setUp(): void
    {
        $this->findAll = $this->createStub(FindAllEntityPort::class);
        $this->formatName = new FormatName();

        $this->listMonster = new ListMonster($this->findAll, $this->formatName);
    }

    /**
     * When the player lists the monsters
     * Then the list result contains the ids and the full names of the monsters
     */
    public function testList(): void
    {
        $this->findAll->method('findAll')->willReturn([
            $this->buildMonster(Uuid::fromString('11111111-1111-1111-1111-111111111111'), 'my_first_name1', 'my_last_name1'),
            $this->buildMonster(Uuid::fromString('22222222-2222-2222-2222-222222222222'), 'my_first_name2', 'my_last_name2'),
        ]);

        $monsters = $this->listMonster->list();

        $this::assertEquals(Uuid::fromString('11111111-1111-1111-1111-111111111111'), $monsters[0]->id);
        $this::assertEquals('my_first_name1 my_last_name1', $monsters[0]->name);
        $this::assertEquals(Uuid::fromString('22222222-2222-2222-2222-222222222222'), $monsters[1]->id);
        $this::assertEquals('my_first_name2 my_last_name2', $monsters[1]->name);
    }

    private function buildMonster(Uuid $id, string $firstName, string $lastName): Monster
    {
        $monster = new Monster();
        $monster->setFirstName($firstName);
        $monster->setLastName($lastName);
        $this->setEntityId($monster, $id);

        return $monster;
    }
}
