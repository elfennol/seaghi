<?php

declare(strict_types=1);

namespace App\Battle\Port\DataContract;

use Symfony\Component\Uid\Uuid;

/**
 * Describe a monster.
 */
readonly class ShowMonsterDto
{
    /**
     * @param ShowEffectDto[] $effects
     */
    public function __construct(
        public Uuid $id,
        public string $name,
        public int $currentHealth,
        public int $maxHealth,
        public int $defense,
        public array $effects,
    ) {
    }
}
