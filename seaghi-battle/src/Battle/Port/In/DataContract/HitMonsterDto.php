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
        public int $id,
        public int $currentHealth,
        public int $healthDiff,
        public array $effects,
    ) {
    }
}
