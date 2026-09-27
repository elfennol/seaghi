<?php

declare(strict_types=1);

namespace App\Battle\Port\DataContract;

use Symfony\Component\Uid\Uuid;

/**
 * Data needed to spawn a monster.
 */
readonly class SpawnMonsterDto
{
    public function __construct(
        public Uuid $id,
        public int $maxHealth,
        public int $defense,
    ) {
    }
}
