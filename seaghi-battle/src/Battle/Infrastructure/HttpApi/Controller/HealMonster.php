<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\HttpApi\Controller;

use App\Battle\Port\In\DataContract\HealMonsterDto;
use App\Battle\Port\In\HealMonsterPort;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

readonly class HealMonster
{
    public function __construct(
        private HealMonsterPort $healMonster,
    ) {
    }

    #[Route('/monster/{monsterId}/heal', methods: ['PUT'])]
    public function __invoke(Uuid $monsterId): HealMonsterDto
    {
        return $this->healMonster->heal($monsterId);
    }
}
