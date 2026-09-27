<?php

declare(strict_types=1);

namespace App\Battle\Application\UseCase;

use App\Battle\Application\Component\Difficulty\DifficultyNormalStrategy;
use App\Battle\Application\Enum\Category;
use App\Battle\Entity\Monster;
use App\Battle\Port\DataContract\SpawnMonsterDto;
use App\Battle\Port\Out\MonsterRepositoryPort;
use App\Battle\Port\Out\TransactionPort;
use Exception;

readonly class SpawnMonster
{
    public function __construct(
        private MonsterRepositoryPort $monsterRepository,
        private DifficultyNormalStrategy $difficultyStrategy,
        private TransactionPort $transaction,
    ) {
    }

    public function spawn(string $firstName, string $lastName, string $category, int $level): SpawnMonsterDto
    {
        if ($level < 2) {
            throw new Exception('Monsters below level 2 are not accepted for battle.');
        }

        $categoryEnum = Category::from($category);
        $health = $this->difficultyStrategy->buildMaxHealth($categoryEnum, $level);
        $defense = $this->difficultyStrategy->buildDefense($categoryEnum);

        $monster = new Monster(
            $firstName,
            $lastName,
            $health,
            $defense,
        );

        $this->transaction->run(function () use ($monster): void {
            $this->monsterRepository->save($monster);
        });

        assert($monster->getId() !== null);

        return new SpawnMonsterDto(
            $monster->getId(),
            $monster->getMaxHealth(),
            $monster->getDefense(),
        );
    }
}
