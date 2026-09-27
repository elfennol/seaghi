<?php

declare(strict_types=1);

namespace App\Battle\Port\DataContract;

use Symfony\Component\Uid\Uuid;

/**
 * Describe a monster in the monster list.
 */
readonly class ListMonsterDto
{
    public function __construct(
        public Uuid $id,
        public string $name,
    ) {
    }
}
