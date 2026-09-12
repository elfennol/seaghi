<?php

declare(strict_types=1);

namespace App\Battle\Port\In\DataContract;

/**
 * An item of the result of the monster list.
 *
 * @see \App\Battle\Port\In\ListMonsterPort
 */
readonly class ListMonsterDto
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
}
