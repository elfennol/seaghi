<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application\UseCase;

use App\Battle\Application\Enum\Category;
use App\Battle\Application\Rule\Difficulty\DifficultyNormalStrategy;
use App\Battle\Application\UseCase\SpawnMonster;
use App\Battle\Port\Out\IdentityGeneratorPort;
use App\Battle\Port\Out\MonsterRepositoryPort;
use App\Battle\Port\Out\TransactionPort;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class SpawnMonsterTest extends TestCase
{
    private SpawnMonster $spawnMonster;
    private MonsterRepositoryPort $monsterRepository;
    private DifficultyNormalStrategy $difficultyStrategy;
    private TransactionPort $transaction;
    private IdentityGeneratorPort $identityGenerator;
    private Uuid $fixedUuid;

    protected function setUp(): void
    {
        $this->monsterRepository = $this->createStub(MonsterRepositoryPort::class);
        $this->difficultyStrategy = new DifficultyNormalStrategy();
        $this->transaction = $this->createStub(TransactionPort::class);
        $this->transaction->method('run')->willReturnCallback(static fn (callable $operation): mixed => $operation());
        $this->fixedUuid = Uuid::fromString('11111111-1111-1111-1111-111111111111');
        $this->identityGenerator = $this->createStub(IdentityGeneratorPort::class);
        $this->identityGenerator->method('generate')->willReturn($this->fixedUuid);

        $this->spawnMonster = new SpawnMonster(
            $this->monsterRepository,
            $this->difficultyStrategy,
            $this->transaction,
            $this->identityGenerator,
        );
    }

    /**
     * When receiving a sold monster message
     * Then this monster is spawned for the battle for a given difficulty
     */
    #[DataProvider('spawnProvider')]
    public function testSpawn(int $expectedDefense, int $expectedHealth, Category $category, int $level): void
    {
        $spawnMonster = $this->spawnMonster->spawn(
            'my_first_name',
            'my_last_name',
            $category->value,
            $level,
        );

        $this::assertSame($this->fixedUuid, $spawnMonster->id);
        $this::assertSame($expectedDefense, $spawnMonster->defense);
        $this::assertSame($expectedHealth, $spawnMonster->maxHealth);
    }

    /**
     * Given a sold monster with a level below 2
     * When receiving this monster
     * Then this monster is not spawned for the battle
     */
    public function testSpawnRequireLevel(): void
    {
        $this->expectException(Exception::class);

        $this->spawnMonster->spawn(
            'my_first_name',
            'my_last_name',
            Category::WILD_SQUIRREL->value,
            1,
        );
    }

    /**
     * [[expected defense, expected health, category, level], ...]
     *
     * @return array<int, array{int, int, Category, int}>
     */
    public static function spawnProvider(): array
    {
        return [
            [5, 40, Category::WILD_SQUIRREL, 2],
            [7, 75, Category::SHAPESHIFTER_CHICKEN, 3],
            [10, 120, Category::LOLCAT, 4],
            [15, 200, Category::CARIBOU_AVENGER, 5],
        ];
    }
}
