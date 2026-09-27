<?php

declare(strict_types=1);

namespace App\Battle\Port\Out;

use App\Battle\Entity\Effect;

/**
 * Effect repository port.
 */
interface EffectRepositoryPort
{
    /**
     * @return array<string, Effect> Array key is the code of Effect
     */
    public function findAllIndexed(): array;
}
