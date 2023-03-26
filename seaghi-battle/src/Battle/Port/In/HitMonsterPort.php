<?php

declare(strict_types=1);

namespace App\Battle\Port\In;

use App\Battle\Port\In\DataContract\HitMonsterDto;

interface HitMonsterPort
{
    public function hit(int $monsterId): HitMonsterDto;
}
