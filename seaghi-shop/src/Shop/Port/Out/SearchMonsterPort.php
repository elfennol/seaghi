<?php

declare(strict_types=1);

namespace App\Shop\Port\Out;

use App\Shop\Port\DataContract\SearchMonsterDto;

/**
 * Search monsters with the given filters.
 */
interface SearchMonsterPort
{
    /**
     * @return iterable<SearchMonsterDto>
     */
    public function search(int $levelMin, int $levelMax): iterable;
}
