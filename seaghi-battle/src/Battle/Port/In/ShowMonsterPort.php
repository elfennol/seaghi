<?php

declare(strict_types=1);

namespace App\Battle\Port\In;

use App\Battle\Port\In\DataContract\ShowMonsterDto;
use Symfony\Component\Uid\Uuid;

/**
 * Show a monster.
 */
interface ShowMonsterPort
{
    public function show(Uuid $monsterId): ShowMonsterDto;
}
