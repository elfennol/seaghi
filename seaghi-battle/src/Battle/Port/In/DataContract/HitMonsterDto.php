<?php

declare(strict_types=1);

namespace App\Battle\Port\In\DataContract;

/**
 * The result of a hit monster.
 *
 * @see \App\Battle\Port\In\HitMonsterPort
 */
readonly class HitMonsterDto
{
    /**
     * @param string[] $effects
     */
    public function __construct(
        private int $id,
        private int $currentHealth,
        private int $healthDiff,
        private array $effects,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCurrentHealth(): int
    {
        return $this->currentHealth;
    }

    public function getHealthDiff(): int
    {
        return $this->healthDiff;
    }

    /**
     * @return string[]
     */
    public function getEffects(): array
    {
        return $this->effects;
    }
}
