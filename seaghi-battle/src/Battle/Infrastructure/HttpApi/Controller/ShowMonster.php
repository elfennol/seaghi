<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Application\UseCase\ShowMonster as ShowMonsterUseCase;
use App\Battle\Port\DataContract\ShowMonsterDto;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

readonly class ShowMonster
{
    public function __construct(
        private ShowMonsterUseCase $showMonster,
    ) {
    }

    #[Route('/monster/{monsterId}', methods: ['GET'])]
    public function __invoke(Uuid $monsterId): ShowMonsterDto
    {
        return $this->showMonster->show($monsterId);
    }
}
