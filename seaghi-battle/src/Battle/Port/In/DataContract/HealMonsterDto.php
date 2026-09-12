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
        public int $id,
        public int $currentHealth,
        public int $healthDiff,
    ) {
    }
}
