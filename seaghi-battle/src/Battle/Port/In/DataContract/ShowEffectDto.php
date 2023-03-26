<?php

declare(strict_types=1);

namespace App\Battle\Port\In\DataContract;

/**
 * Sub result return when a monster is shown.
 *
 * @see \App\Battle\Port\In\ShowMonsterPort
 */
readonly class ShowEffectDto
{
    public function __construct(
        private string $effectCode,
    ) {
    }

    public function getEffectCode(): string
    {
        return $this->effectCode;
    }
}
