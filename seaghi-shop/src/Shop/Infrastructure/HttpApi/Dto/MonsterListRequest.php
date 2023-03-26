<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\HttpApi\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * The data needed to filter the list of monsters.
 */
readonly class MonsterListRequest
{
    /**
     * @Assert\Positive
     */
    public int|null $levelMin;

    /**
     * @Assert\Positive
     */
    public int|null $levelMax;

    public function __construct(?int $levelMin, ?int $levelMax)
    {
        $this->levelMin = $levelMin;
        $this->levelMax = $levelMax;
    }
}
