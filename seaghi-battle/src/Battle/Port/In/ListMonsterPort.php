<?php

declare(strict_types=1);

namespace App\Battle\Port\In;

use App\Battle\Port\In\DataContract\ListMonsterDto;

/**
 * List the monsters.
 */
interface ListMonsterPort
{
    /**
     * @return array<int, ListMonsterDto>
     */
    public function list(): array;
}
