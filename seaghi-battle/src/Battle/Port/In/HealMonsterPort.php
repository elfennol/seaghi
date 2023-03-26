<?php

declare(strict_types=1);

namespace App\Battle\Port\In;

use App\Battle\Port\In\DataContract\HealMonsterDto;

interface HealMonsterPort
{
    public function heal(int $monsterId): HealMonsterDto;
}
