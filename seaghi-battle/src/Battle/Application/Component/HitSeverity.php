<?php

declare(strict_types=1);

namespace App\Battle\Application\Component;

use App\Battle\Application\Enum\HitForce;

/**
 * Hit severity result.
 */
readonly class HitSeverity
{
    public function __construct(
        private int $rollResult,
        private HitForce $hitForce,
    ) {
    }

    public function getRollResult(): int
    {
        return $this->rollResult;
    }

    public function getHitForce(): HitForce
    {
        return $this->hitForce;
    }
}
