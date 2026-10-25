<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\HttpApi\Controller;

use App\Shop\Application\UseCase\BuyMonster as BuyMonsterUseCase;
use App\Shop\Port\DataContract\BuyMonsterDto;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

readonly class BuyMonster
{
    public function __construct(
        private BuyMonsterUseCase $buyMonster,
    ) {
    }

    #[Route('/monster/buy/{monsterId}', methods: ['PUT'])]
    public function __invoke(Uuid $monsterId): BuyMonsterDto
    {
        return $this->buyMonster->execute($monsterId);
    }
}
