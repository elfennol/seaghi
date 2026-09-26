<?php

declare(strict_types=1);

namespace App\Battle\Port\In;

use App\Battle\Port\In\DataContract\HitMonsterDto;
use Symfony\Component\Uid\Uuid;

interface HitMonsterPort
{
    public function hit(Uuid $monsterId): HitMonsterDto;
}
