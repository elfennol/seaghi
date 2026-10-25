<?php

declare(strict_types=1);

namespace App\Shop\Application\UseCase;

use App\Shop\Port\DataContract\SearchMonsterDto;
use App\Shop\Port\Out\SearchMonsterPort;
use InvalidArgumentException;

/**
 * The player is not allowed to see a monster with a level > 10 in the list.
 * In the future some rights may be defined to allow some players to see these monsters.
 *
 * The requested levels must be between 1 and 10, otherwise the request is rejected.
 */
readonly class ListMonster
{
    public function __construct(
        private SearchMonsterPort $searchMonster,
    ) {
    }

    /**
     * @return iterable<SearchMonsterDto>
     */
    public function execute(int $levelMin, int $levelMax): iterable
    {
        if ($levelMin < 1 || $levelMin > 10 || $levelMax < 1 || $levelMax > 10) {
            throw new InvalidArgumentException('Allowed levels are between 1 and 10.');
        }

        return $this->searchMonster->search($levelMin, $levelMax);
    }
}
