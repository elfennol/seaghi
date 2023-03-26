<?php

declare(strict_types=1);

namespace App\Battle\Port\Out;

use App\Battle\Entity\Effect;

/**
 * Find all effects and return an indexed array with the code of the effect.
 */
interface FindAllEffectIndexedPort
{
    /**
     * [code => Effect, ...]
     *
     * @return Effect[]
     */
    public function findAllIndexed(): array;
}
