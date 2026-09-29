<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application\UseCase;

use App\Battle\Application\Rule\ComputeHealing;
use App\Battle\Application\Rule\Dice\RollDice;
use App\Battle\Application\UseCase\HealMonster;
use App\Battle\Entity\Monster;
use App\Battle\Port\Out\MonsterRepositoryPort;
use App\Battle\Port\Out\PickRandomIntPort;
use App\Battle\Port\Out\TransactionPort;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class HealMonsterTest extends TestCase
{
    private HealMonster $healMonster;
    private MonsterRepositoryPort $monsterRepository;
    private PickRandomIntPort $pickRandomInt;
    private ComputeHealing $computeHeal;
    private TransactionPort $transaction;

    protected function setUp(): void
    {
        $this->pickRandomInt = $this->createStub(PickRandomIntPort::class);
        $this->monsterRepository = $this->createStub(MonsterRepositoryPort::class);
        $this->computeHeal = new ComputeHealing(new RollDice($this->pickRandomInt));
        $this->transaction = $this->createStub(TransactionPort::class);
        $this->transaction->method('run')->willReturnCallback(static fn (callable $operation): mixed => $operation());

        $this->healMonster = new HealMonster(
            $this->monsterRepository,
            $this->transaction,
            $this->computeHeal,
        );
    }

    /**
     * Given a Monster
     * When the player heals this monster
     * Then this monster has a better health but never above its max health
     *
     * @dataProvider healProvider
     */
    #[DataProvider('healProvider')]
    public function testHeal(int $expectedHealth, int $providedRandomInt): void
    {
        $this->pickRandomInt->method('pickRandomInt')->willReturn($providedRandomInt);
        $monster = new Monster(Uuid::fromString('11111111-1111-1111-1111-111111111111'), 'my_first_name', 'my_last_name', 20, 10, 10);

        $this->monsterRepository->method('get')
            ->willReturn($monster);

        $this::assertEquals($expectedHealth, $this->healMonster->heal(Uuid::fromString('11111111-1111-1111-1111-111111111111'))->currentHealth);
    }

    /**
     * [[expected health, provided random int], ...]
     *
     * @return array<int, array{int, int}>
     */
    public static function healProvider(): array
    {
        return [
            [16, 2],
            [20, 7],
        ];
    }
}
