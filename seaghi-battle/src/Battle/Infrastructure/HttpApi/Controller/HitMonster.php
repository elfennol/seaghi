<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Application\UseCase\HitMonster as HitMonsterUseCase;
use App\Battle\Port\DataContract\HitMonsterDto;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

readonly class HitMonster
{
    public function __construct(
        private HitMonsterUseCase $hitMonster,
    ) {
    }

    #[Route('/monster/{monsterId}/hit', methods: ['PUT'])]
    public function __invoke(Uuid $monsterId): HitMonsterDto
    {
        return $this->hitMonster->hit($monsterId);
    }
}
