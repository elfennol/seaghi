<?php

declare(strict_types=1);

namespace App\Battle\Port\In\DataContract;

use Symfony\Component\Uid\Uuid;

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
        public Uuid $id,
        public int $currentHealth,
        public int $healthDiff,
        public array $effects,
    ) {
    }
}
