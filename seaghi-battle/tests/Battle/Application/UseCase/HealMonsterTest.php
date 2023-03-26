<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application\UseCase;

use App\Battle\Application\Component\ComputeHealing;
use App\Battle\Application\Component\Dice\RollDice;
use App\Battle\Application\Component\ProcessHealth;
use App\Battle\Entity\Monster;
use App\Battle\Application\UseCase\HealMonster;
use App\Battle\Port\Out\FindEntityPort;
use App\Battle\Port\Out\PersistEntityPort;
use App\Battle\Port\Out\PickRandomIntPort;
use App\Tests\Battle\Application\EntityIdSetterTrait;
use PHPUnit\Framework\TestCase;

class HealMonsterTest extends TestCase
{
    use EntityIdSetterTrait;

    private HealMonster $healMonster;
    private FindEntityPort $findEntity;
    private PersistEntityPort $persistEntity;
    private PickRandomIntPort $pickRandomInt;
    private ComputeHealing $computeHeal;
    private ProcessHealth $processHealth;


    /**
     * Given a Monster
     * When the player heals this monster
     * Then this monster has a better health but never above its max health
     *
     * @dataProvider healProvider
     */
    public function testHeal(int $expectedHealth, int $providedRandomInt): void
    {
        $this->pickRandomInt->method('pickRandomInt')->willReturn($providedRandomInt);
        $monster = new Monster();
        $monster->setFirstName('my_first_name');
        $monster->setLastName('my_last_name');
        $monster->setMaxHealth(20);
        $monster->setCurrentHealth(10);
        $monster->setDefense(10);
        $this->setEntityId($monster, 1);

        $this->findEntity->method('find')
            ->willReturn($monster);

        $this::assertEquals($expectedHealth, $this->healMonster->heal(1)->getCurrentHealth());
    }

    /**
     * [[expected health, provided random int], ...]
     */
    public function healProvider(): array
    {
        return [
            [16, 2],
            [20, 7]
        ];
    }

    protected function setUp(): void
    {
        $this->pickRandomInt = $this->createMock(PickRandomIntPort::class);

        $this->findEntity = $this->createMock(FindEntityPort::class);
        $this->persistEntity = $this->createMock(PersistEntityPort::class);
        $this->computeHeal = new ComputeHealing(new RollDice($this->pickRandomInt));
        $this->processHealth = new ProcessHealth();

        $this->healMonster = new HealMonster(
            $this->findEntity,
            $this->persistEntity,
            $this->computeHeal,
            $this->processHealth,
        );
    }
}
