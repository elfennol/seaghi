<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Application\UseCase\HealMonster as HealMonsterUseCase;
use App\Battle\Port\DataContract\HealMonsterDto;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

readonly class HealMonster
{
    public function __construct(
        private HealMonsterUseCase $healMonster,
    ) {
    }

    #[Route('/monster/{monsterId}/heal', methods: ['PUT'])]
    public function __invoke(Uuid $monsterId): HealMonsterDto
    {
        return $this->healMonster->heal($monsterId);
    }
}
