<?php

declare(strict_types=1);

namespace App\Battle\Application\UseCase;

use App\Battle\Application\Component\Difficulty\DifficultyNormalStrategy;
use App\Battle\Application\Enum\Category;
use App\Battle\Port\In\DataContract\SpawnMonsterDto;
use App\Battle\Entity\Monster;
use App\Battle\Port\In\SpawnMonsterPort;
use App\Battle\Port\Out\PersistEntityPort;
use Exception;

readonly class SpawnMonster implements SpawnMonsterPort
{
    public function __construct(
        private PersistEntityPort $persistEntity,
        private DifficultyNormalStrategy $difficultyStrategy,
    ) {
    }

    public function spawn(string $firstName, string $lastName, string $category, int $level): SpawnMonsterDto
    {
        if ($level < 2) {
            throw new Exception('Monsters below level 2 are not accepted for battle.');
        }

        $category = Category::from($category);

        $monster = new Monster();
        $monster->setFirstName($firstName);
        $monster->setLastName($lastName);
        $health = $this->difficultyStrategy->buildMaxHealth($category, $level);
        $monster->setMaxHealth($health);
        $monster->setCurrentHealth($health);
        $monster->setDefense($this->difficultyStrategy->buildDefense($category));

        $this->persistEntity->persist($monster);

        return new SpawnMonsterDto(
            $monster->getId(),
            $monster->getMaxHealth(),
            $monster->getDefense(),
        );
    }
}
