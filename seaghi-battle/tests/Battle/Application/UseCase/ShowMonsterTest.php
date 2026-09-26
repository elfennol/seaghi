<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application\UseCase;

use App\Battle\Application\Component\FormatName;
use App\Battle\Entity\Monster;
use App\Battle\Application\UseCase\ShowMonster;
use App\Battle\Port\Out\FindEntityPort;
use App\Tests\Battle\Application\EntityIdSetterTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ShowMonsterTest extends TestCase
{
    use EntityIdSetterTrait;

    private ShowMonster $showMonster;
    private FindEntityPort $findEntity;
    private FormatName $formatName;

    /**
     * Given a Monster
     * When the player shows a monsters
     * Then the result contains the id, full name, max health, current health and defense.
     */
    public function testShow(): void
    {
        $this->findEntity->method('find')
            ->willReturn($this->buildMonster(Uuid::fromString('11111111-1111-1111-1111-111111111111'), 'my_first_name1', 'my_last_name1'));

        $monster = $this->showMonster->show(Uuid::fromString('11111111-1111-1111-1111-111111111111'));

        $this::assertEquals(Uuid::fromString('11111111-1111-1111-1111-111111111111'), $monster->id);
        $this::assertEquals('my_first_name1 my_last_name1', $monster->name);
        $this::assertEquals(20, $monster->maxHealth);
        $this::assertEquals(10, $monster->currentHealth);
        $this::assertEquals(11, $monster->defense);
    }

    protected function setUp(): void
    {
        $this->findEntity = $this->createStub(FindEntityPort::class);
        $this->formatName = new FormatName();

        $this->showMonster = new ShowMonster($this->findEntity, $this->formatName);
    }

    private function buildMonster(Uuid $id, string $firstName, string $lastName): Monster
    {
        $monster = new Monster();
        $monster->setFirstName($firstName);
        $monster->setLastName($lastName);
        $monster->setMaxHealth(20);
        $monster->setCurrentHealth(10);
        $monster->setDefense(11);
        $this->setEntityId($monster, $id);

        return $monster;
    }
}
