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
        public int $id,
        public string $name,
        public int $currentHealth,
        public int $maxHealth,
        public int $defense,
        public array $effects,
    ) {
    }
}
