<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Port\In\DataContract\HitMonsterDto;
use App\Battle\Port\In\HitMonsterPort;
use Symfony\Component\Routing\Annotation\Route;

readonly class HitMonster
{
    public function __construct(
        private HitMonsterPort $hitMonster,
    ) {
    }

    #[Route('/monster/{monsterId}/hit', methods: ['PUT'])]
    public function __invoke(int $monsterId): HitMonsterDto
    {
        return $this->hitMonster->hit($monsterId);
    }
}
