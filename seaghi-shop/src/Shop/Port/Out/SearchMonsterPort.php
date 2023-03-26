<?php

declare(strict_types=1);

namespace App\Shop\Port\Out;

use App\Shop\Entity\Monster;

/**
 * Search monsters with the given filters.
 */
interface SearchMonsterPort
{
    /**
     * @return Monster[]
     */
    public function search(int $levelMin, int $levelMax): iterable;
}
