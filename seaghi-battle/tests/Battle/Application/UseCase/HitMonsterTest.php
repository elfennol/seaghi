<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application\UseCase;

use App\Battle\Application\Component\ComputeDamageSeverity;
use App\Battle\Application\Component\ComputeHitSeverity;
use App\Battle\Application\Component\Dice\RollDice;
use App\Battle\Application\Component\ProcessHealth;
use App\Battle\Entity\Effect;
use App\Battle\Entity\Monster;
use App\Battle\Application\UseCase\HitMonster;
use App\Battle\Port\Out\FindAllEffectIndexedPort;
use App\Battle\Port\Out\FindEntityPort;
use App\Battle\Port\Out\PersistEntityPort;
use App\Battle\Port\Out\PickRandomIntPort;
use App\Tests\Battle\Application\EntityIdSetterTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class HitMonsterTest extends TestCase
{
    use EntityIdSetterTrait;

    private HitMonster $hitMonster;
    private FindEntityPort $findEntity;
    private FindAllEffectIndexedPort $findAllEffectIndexed;
    private PersistEntityPort $persistEntity;
    private ComputeHitSeverity $computeHitSeverity;
    private ComputeDamageSeverity $computeDmgSeverity;
    private ProcessHealth $processHealth;
    private PickRandomIntPort $pickRandomInt;

    /**
     * Given a Monster
     * When the player hits this monster with a roll result lesser or equal than the monster defense
     * Then the health of this monster does not change
     *
     * @dataProvider hitProviderBelowDefense
     */
    #[DataProvider('hitProviderBelowDefense')]
    public function testHitBelowDefense(int $expectedHealth, int $providedRandomInt, int $defense): void
    {
        $this->pickRandomInt->method('pickRandomInt')->willReturn($providedRandomInt);
        $this->findEntity->method('find')
            ->willReturn($this->buildMonster($defense));

        $this::assertEquals($expectedHealth, $this->hitMonster->hit(Uuid::fromString('11111111-1111-1111-1111-111111111111'))->currentHealth);
    }

    /**
     * Given a Monster
     * When the player hits this monster with a roll result greater than the monster defense
     * Then the health of this monster lower but never less than 0
     *
     * @dataProvider hitProviderAboveDefense
     */
    #[DataProvider('hitProviderAboveDefense')]
    public function testHitAboveDefense(int $expectedHealth, int $providedRandomInt, int $defense): void
    {
        $this->pickRandomInt->method('pickRandomInt')->willReturn($providedRandomInt);
        $this->findEntity->method('find')
            ->willReturn($this->buildMonster($defense));

        $this::assertEquals($expectedHealth, $this->hitMonster->hit(Uuid::fromString('11111111-1111-1111-1111-111111111111'))->currentHealth);
    }

    /**
     * Given a Monster
     * When the player hits this monster with a maximum roll result
     * Then this monster health is reduced with twice this result and the monster has serious injury
     */
    public function testHitCritical(): void
    {
        $this->pickRandomInt->method('pickRandomInt')->willReturn(20);
        $this->findEntity->method('find')
            ->willReturn($this->buildMonster(10, 100, 50));

        $hitResult = $this->hitMonster->hit(Uuid::fromString('11111111-1111-1111-1111-111111111111'));
        $this::assertEquals(10, $hitResult->currentHealth);
        $this::assertContains(Effect::CODE_SERIOUS_INJURY, $hitResult->effects);
    }

    /**
     * Given a Monster
     * When the player hits this monster without hurting the monster
     * Then this monster is badass
     */
    public function testHitBadass(): void
    {
        $this->pickRandomInt->method('pickRandomInt')->willReturn(2);
        $this->findEntity->method('find')
            ->willReturn($this->buildMonster(10));

        $hitResult = $this->hitMonster->hit(Uuid::fromString('11111111-1111-1111-1111-111111111111'));
        $this::assertContains(Effect::CODE_BADASS, $hitResult->effects);
    }

    /**
     * [[expected health, provided random int, defense], ...]
     */
    public static function hitProviderBelowDefense(): array
    {
        return [
            [10, 2, 8],
            [10, 8, 8],
        ];
    }

    /**
     * [[expected health, provided random int, defense], ...]
     */
    public static function hitProviderAboveDefense(): array
    {
        return [
            [1, 9, 8],
            [0, 11, 8],
        ];
    }

    protected function setUp(): void
    {
        $this->pickRandomInt = $this->createStub(PickRandomIntPort::class);

        $this->findEntity = $this->createStub(FindEntityPort::class);
        $this->findAllEffectIndexed = $this->createStub(FindAllEffectIndexedPort::class);
        $this->persistEntity = $this->createStub(PersistEntityPort::class);
        $this->computeHitSeverity = new ComputeHitSeverity(new RollDice($this->pickRandomInt));
        $this->computeDmgSeverity = new ComputeDamageSeverity();
        $this->processHealth = new ProcessHealth();

        $this->hitMonster = new HitMonster(
            $this->findEntity,
            $this->findAllEffectIndexed,
            $this->persistEntity,
            $this->computeHitSeverity,
            $this->computeDmgSeverity,
            $this->processHealth,
        );
    }

    private function buildMonster(int $defense, int $maxHealth = 20, int $currentHealth = 10): Monster
    {
        $monster = new Monster();
        $monster->setFirstName('my_first_name');
        $monster->setLastName('my_last_name');
        $monster->setMaxHealth($maxHealth);
        $monster->setCurrentHealth($currentHealth);
        $monster->setDefense($defense);
        $this->setEntityId($monster, Uuid::fromString('11111111-1111-1111-1111-111111111111'));

        return $monster;
    }
}
