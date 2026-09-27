<?php

declare(strict_types=1);

namespace App\Battle\Port\DataContract;

/**
 * Describe an effect for a monster.
 */
readonly class ShowEffectDto
{
    public function __construct(
        public string $code,
    ) {
    }
}
