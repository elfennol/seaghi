<?php

declare(strict_types=1);

namespace App\Battle\Port\In;

use App\Battle\Port\In\DataContract\HealMonsterDto;
use Symfony\Component\Uid\Uuid;

interface HealMonsterPort
{
    public function heal(Uuid $monsterId): HealMonsterDto;
}
