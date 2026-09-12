<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\HttpApi\Controller;

use App\Shop\Infrastructure\HttpApi\Dto\MonsterListRequest;
use App\Shop\Port\In\DataContract\SearchMonsterDto;
use App\Shop\Port\In\ListItemPort;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

readonly class ListMonster
{
    public function __construct(
        private ListItemPort $listMonster,
    ) {
    }

    /**
     * @return SearchMonsterDto[]
     */
    #[Route('/monster/list', methods: ['GET'])]
    public function __invoke(#[MapQueryString] MonsterListRequest $monsterListRequest): iterable
    {
        return $this->listMonster->list($monsterListRequest->levelMin, $monsterListRequest->levelMax);
    }
}
