<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Application\UseCase\ListMonster as ListMonsterUseCase;
use App\Battle\Port\DataContract\ListMonsterDto;
use Symfony\Component\Routing\Attribute\Route;

readonly class ListMonster
{
    public function __construct(
        private ListMonsterUseCase $listMonster,
    ) {
    }

    /**
     * @return ListMonsterDto[]
     */
    #[Route('/monster/list', methods: ['GET'])]
    public function __invoke(): array
    {
        return $this->listMonster->list();
    }
}
