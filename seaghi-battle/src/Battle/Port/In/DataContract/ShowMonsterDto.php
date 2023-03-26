<?php

declare(strict_types=1);

namespace App\Battle\Port\In\DataContract;

/**
 * Result return when a monster is shown.
 *
 * @see \App\Battle\Port\In\ShowMonsterPort
 */
readonly class ShowMonsterDto
{
    /**
     * @param ShowEffectDto[] $effects
     */
    public function __construct(
        private int $id,
        private string $name,
        private int $currentHealth,
        private int $maxHealth,
        private int $defense,
        private array $effects,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCurrentHealth(): int
    {
        return $this->currentHealth;
    }

    public function getMaxHealth(): int
    {
        return $this->maxHealth;
    }

    public function getDefense(): int
    {
        return $this->defense;
    }

    /**
     * @return ShowEffectDto[]
     */
    public function getEffects(): array
    {
        return $this->effects;
    }
}
