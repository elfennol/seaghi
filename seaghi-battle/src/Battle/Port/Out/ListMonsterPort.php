<?php

declare(strict_types=1);

namespace App\Battle\Port\Out;

use App\Battle\Port\DataContract\ListMonsterDto;

interface ListMonsterPort
{
    /**
     * @return iterable<ListMonsterDto>
     */
    public function list(): iterable;
}
