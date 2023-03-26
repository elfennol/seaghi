<?php

declare(strict_types=1);

namespace App\Battle\Port\In;

use App\Battle\Port\In\DataContract\SpawnMonsterDto;

/**
 * Spawn a sold monster for the battle.
 */
interface SpawnMonsterPort
{
    public function spawn(string $firstName, string $lastName, string $category, int $level): SpawnMonsterDto;
}
