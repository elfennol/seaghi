<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Port\In\DataContract\ShowMonsterDto;
use App\Battle\Port\In\ShowMonsterPort;
use Symfony\Component\Routing\Annotation\Route;

readonly class ShowMonster
{
    public function __construct(
        private ShowMonsterPort $showMonster,
    ) {
    }

    #[Route('/monster/{monsterId}/show', methods: ['GET'])]
    public function __invoke(int $monsterId): ShowMonsterDto
    {
        return $this->showMonster->show($monsterId);
    }
}
