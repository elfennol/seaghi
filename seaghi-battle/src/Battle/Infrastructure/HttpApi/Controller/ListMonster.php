<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Port\In\DataContract\ListMonsterDto;
use App\Battle\Port\In\ListMonsterPort;
use Symfony\Component\Routing\Annotation\Route;

readonly class ListMonster
{
    public function __construct(
        private ListMonsterPort $listMonster,
    ) {
    }

    /**
     * @return array<int, ListMonsterDto>
     */
    #[Route('/monster/list', methods: ['GET'])]
    public function __invoke(): array
    {
        return $this->listMonster->list();
    }
}
