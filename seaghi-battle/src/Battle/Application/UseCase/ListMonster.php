<?php

declare(strict_types=1);

namespace App\Battle\Application\UseCase;

use App\Battle\Port\DataContract\ListMonsterDto;
use App\Battle\Port\Out\ListMonsterPort;

readonly class ListMonster
{
    public function __construct(
        private ListMonsterPort $listMonster,
    ) {
    }

    /**
     * @return iterable<ListMonsterDto>
     */
    public function list(): iterable
    {
        return $this->listMonster->list();
    }
}
