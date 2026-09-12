<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\HttpApi\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * The data needed to filter the list of monsters.
 */
readonly class MonsterListRequest
{
    public function __construct(
        #[Assert\Positive]
        #[Assert\NotNull]
        public int $levelMin,
        #[Assert\Positive]
        #[Assert\NotNull]
        public int $levelMax,
    ) {
    }
}
