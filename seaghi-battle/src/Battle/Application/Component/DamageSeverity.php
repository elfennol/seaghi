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
        private int $amount,
        private array $effects,
    ) {
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return array<string>
     */
    public function getEffects(): array
    {
        return $this->effects;
    }
}
