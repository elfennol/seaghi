<?php

declare(strict_types=1);

namespace App\Battle\Port\In\DataContract;

use Symfony\Component\Uid\Uuid;

/**
 * The result of a created monster.
 *
 * @see \App\Battle\Port\In\SpawnMonsterPort
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
