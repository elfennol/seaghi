<?php

declare(strict_types=1);

namespace App\Battle\Port\DataContract;

use Symfony\Component\Uid\Uuid;

/**
 * The result of a heal monster.
 */
readonly class HealMonsterDto
{
    public function __construct(
        public Uuid $id,
        public int $currentHealth,
        public int $healthDiff,
    ) {
    }
}
