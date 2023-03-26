<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application\UseCase;

use App\Battle\Application\Component\Difficulty\DifficultyNormalStrategy;
use App\Battle\Application\Enum\Category;
use App\Battle\Entity\Monster;
use App\Battle\Application\UseCase\SpawnMonster;
use App\Battle\Port\Out\PersistEntityPort;
use App\Tests\Battle\Application\EntityIdSetterTrait;
use Exception;
use PHPUnit\Framework\TestCase;

class SpawnMonsterTest extends TestCase
{
    use EntityIdSetterTrait;

    private SpawnMonster $spawnMonster;
    private PersistEntityPort $persistEntity;
    private DifficultyNormalStrategy $difficultyStrategy;

    /**
     * When receiving a sold monster message
     * Then this monster is spawned for the battle for a given difficulty
     *
     * @dataProvider spawnProvider
     */
    public function testSpawn(int $expectedDefense, int $expectedHealth, Category $category, int $level): void
    {
        $this->persistEntity->method('persist')
            ->willReturnCallback(function (Monster $monster) {
                $this->setEntityId($monster, 1);
            });

        $spawnMonster = $this->spawnMonster->spawn(
            'my_first_name',
            'my_last_name',
            $category->value,
            $level,
        );

        $this::assertEquals($expectedDefense, $spawnMonster->getDefense());
        $this::assertEquals($expectedHealth, $spawnMonster->getMaxHealth());
    }

    /**
     * Given a sold monster with a level below 2
     * When receiving this monster
     * Then this monster is not spawned for the battle
     */
    public function testSpawnRequireLevel(): void
    {
        $this->expectException(Exception::class);

        $this->persistEntity->method('persist')
            ->willReturnCallback(function (Monster $monster) {
                $this->setEntityId($monster, 1);
            });

        $this->spawnMonster->spawn(
            'my_first_name',
            'my_last_name',
            Category::WILD_SQUIRREL->value,
            1,
        );
    }

    /**
     * [[expected defense, expected health, category, level], ...]
     */
    public function spawnProvider(): array
    {
        return [
            [5, 40, Category::WILD_SQUIRREL, 2],
            [7, 75, Category::SHAPESHIFTER_CHICKEN, 3],
            [10, 120, Category::LOLCAT, 4],
            [15, 200, Category::CARIBOU_AVENGER, 5],
        ];
    }

    protected function setUp(): void
    {
        $this->persistEntity = $this->createMock(PersistEntityPort::class);
        $this->difficultyStrategy = new DifficultyNormalStrategy();

        $this->spawnMonster = new SpawnMonster(
            $this->persistEntity,
            $this->difficultyStrategy,
        );
    }
}
