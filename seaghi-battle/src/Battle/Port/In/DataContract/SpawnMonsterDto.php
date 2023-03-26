<?php

declare(strict_types=1);

namespace App\Battle\Port\In\DataContract;

/**
 * The result of a created monster.
 *
 * @see \App\Battle\Port\In\SpawnMonsterPort
 */
readonly class SpawnMonsterDto
{
    public function __construct(
        private int $id,
        private int $maxHealth,
        private int $defense,
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getMaxHealth(): int
    {
        return $this->maxHealth;
    }

    /**
     * @return int
     */
    public function getDefense(): int
    {
        return $this->defense;
    }
}
