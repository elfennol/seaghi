<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application\UseCase;

use App\Battle\Application\UseCase\ShowMonster;
use App\Battle\Entity\Monster;
use App\Battle\Port\Out\MonsterRepositoryPort;
use App\Tests\Battle\Application\EntityIdSetterTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ShowMonsterTest extends TestCase
{
    use EntityIdSetterTrait;

    private ShowMonster $showMonster;
    private MonsterRepositoryPort $monsterRepository;

    protected function setUp(): void
    {
        $this->monsterRepository = $this->createStub(MonsterRepositoryPort::class);
        $this->showMonster = new ShowMonster($this->monsterRepository);
    }

    /**
     * Given a Monster
     * When the player shows a monsters
     * Then the result contains the id, full name, max health, current health and defense.
     */
    public function testShow(): void
    {
        $this->monsterRepository->method('get')
            ->willReturn($this->buildMonster(Uuid::fromString('11111111-1111-1111-1111-111111111111'), 'my_first_name1', 'my_last_name1'));

        $monster = $this->showMonster->show(Uuid::fromString('11111111-1111-1111-1111-111111111111'));

        $this::assertEquals(Uuid::fromString('11111111-1111-1111-1111-111111111111'), $monster->id);
        $this::assertEquals('my_first_name1 my_last_name1', $monster->name);
        $this::assertEquals(20, $monster->maxHealth);
        $this::assertEquals(10, $monster->currentHealth);
        $this::assertEquals(11, $monster->defense);
    }

    private function buildMonster(Uuid $id, string $firstName, string $lastName): Monster
    {
        $monster = new Monster($firstName, $lastName, 20, 11, 10);
        $this->setEntityId($monster, $id);

        return $monster;
    }
}
