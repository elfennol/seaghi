<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\HttpApi\Controller;

use App\Shop\Port\In\BuyItemPort;
use App\Shop\Port\In\DataContract\BuyItemDto;
use Symfony\Component\Routing\Annotation\Route;

readonly class BuyMonster
{
    public function __construct(
        private BuyItemPort $buyMonster,
    ) {
    }

    #[Route('/monster/buy/{monsterId}', methods: ['PUT'])]
    public function __invoke(int $monsterId): BuyItemDto
    {
        return $this->buyMonster->buy($monsterId);
    }
}
