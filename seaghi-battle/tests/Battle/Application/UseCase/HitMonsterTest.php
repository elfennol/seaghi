<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application\UseCase;

use App\Battle\Application\Rule\ComputeDamageSeverity;
use App\Battle\Application\Rule\ComputeHitSeverity;
use App\Battle\Application\Rule\Dice\RollDice;
use App\Battle\Application\UseCase\HitMonster;
use App\Battle\Entity\Effect;
use App\Battle\Entity\Monster;
use App\Battle\Port\Out\EffectRepositoryPort;
use App\Battle\Port\Out\MonsterRepositoryPort;
use App\Battle\Port\Out\PickRandomIntPort;
use App\Battle\Port\Out\TransactionPort;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class HitMonsterTest extends TestCase
{
    private HitMonster $hitMonster;
    private MonsterRepositoryPort $monsterRepository;
    private EffectRepositoryPort $effectRepository;
    private TransactionPort $transaction;
    private ComputeHitSeverity $computeHitSeverity;
    private ComputeDamageSeverity $computeDmgSeverity;
    private PickRandomIntPort $pickRandomInt;

    protected function setUp(): void
    {
        $this->pickRandomInt = $this->createStub(PickRandomIntPort::class);
        $this->monsterRepository = $this->createStub(MonsterRepositoryPort::class);
        $this->effectRepository = $this->createStub(EffectRepositoryPort::class);
        $this->transaction = $this->createStub(TransactionPort::class);
        $this->transaction->method('run')->willReturnCallback(static fn (callable $operation): mixed => $operation());
        $this->computeHitSeverity = new ComputeHitSeverity(new RollDice($this->pickRandomInt));
        $this->computeDmgSeverity = new ComputeDamageSeverity();

        $this->hitMonster = new HitMonster(
            $this->monsterRepository,
            $this->effectRepository,
            $this->transaction,
            $this->computeHitSeverity,
            $this->computeDmgSeverity,
        );
    }

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
        $this->monsterRepository->method('get')
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
        $this->monsterRepository->method('get')
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
        $this->monsterRepository->method('get')
            ->willReturn($this->buildMonster(10, 100, 50));

        $seriousInjuryEffect = new Effect();
        $seriousInjuryEffect->setCode(Effect::CODE_SERIOUS_INJURY);
        $this->effectRepository->method('findAllIndexed')->willReturn([
            Effect::CODE_SERIOUS_INJURY => $seriousInjuryEffect,
        ]);

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
        $this->monsterRepository->method('get')
            ->willReturn($this->buildMonster(10));

        $badassEffect = new Effect();
        $badassEffect->setCode(Effect::CODE_BADASS);
        $this->effectRepository->method('findAllIndexed')->willReturn([
            Effect::CODE_BADASS => $badassEffect,
        ]);

        $hitResult = $this->hitMonster->hit(Uuid::fromString('11111111-1111-1111-1111-111111111111'));
        $this::assertContains(Effect::CODE_BADASS, $hitResult->effects);
    }

    /**
     * [[expected health, provided random int, defense], ...]
     *
     * @return array<int, array{int, int, int}>
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
     *
     * @return array<int, array{int, int, int}>
     */
    public static function hitProviderAboveDefense(): array
    {
        return [
            [1, 9, 8],
            [0, 11, 8],
        ];
    }

    private function buildMonster(int $defense, int $maxHealth = 20, int $currentHealth = 10): Monster
    {
        return new Monster(Uuid::fromString('11111111-1111-1111-1111-111111111111'), 'my_first_name', 'my_last_name', $maxHealth, $defense, $currentHealth);
    }
}
