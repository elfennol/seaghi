<?php

declare(strict_types=1);

namespace App\Battle\Application\Component;

/**
 * Damage severity result.
 */
readonly class DamageSeverity
{
    /**
     * @param array<string> $effects
     */
    public function __construct(
        public int $amount,
        public array $effects,
    ) {
    }
}
