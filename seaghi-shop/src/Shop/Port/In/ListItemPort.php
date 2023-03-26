<?php

declare(strict_types=1);

namespace App\Shop\Port\In;

use App\Shop\Port\In\DataContract\SearchMonsterDto;

/**
 * List the monster of the shop.
 */
interface ListItemPort
{
    /**
     * @return SearchMonsterDto[]
     */
    public function list(int|null $levelMin, int|null $levelMax): iterable;
}
