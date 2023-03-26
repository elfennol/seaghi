<?php

declare(strict_types=1);

namespace App\Battle\Application\Component;

/**
 * Hit severity result.
 */
readonly class HitSeverity
{
    public function __construct(
        private int $rollResult,
        private string $hitForce,
    ) {
    }

    public function getRollResult(): int
    {
        return $this->rollResult;
    }

    public function getHitForce(): string
    {
        return $this->hitForce;
    }
}
