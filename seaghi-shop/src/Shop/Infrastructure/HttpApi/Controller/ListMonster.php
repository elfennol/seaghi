<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\HttpApi\Controller;

use App\Shop\Application\UseCase\ListItem;
use App\Shop\Infrastructure\HttpApi\Dto\MonsterListRequest;
use App\Shop\Port\DataContract\SearchMonsterDto;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

readonly class ListMonster
{
    public function __construct(
        private ListItem $listMonster,
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
