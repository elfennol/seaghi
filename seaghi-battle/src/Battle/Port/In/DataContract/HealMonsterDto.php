<?php

declare(strict_types=1);

namespace App\Battle\Port\In\DataContract;

/**
 * The result of a healed monster.
 *
 * @see \App\Battle\Port\In\HealMonsterPort
 */
readonly class HealMonsterDto
{
    public function __construct(
        private int $id,
        private int $currentHealth,
        private int $healthDiff,
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
}
