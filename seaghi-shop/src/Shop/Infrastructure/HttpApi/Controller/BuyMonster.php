<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\HttpApi\Controller;

use App\Shop\Application\UseCase\BuyItem;
use App\Shop\Port\DataContract\BuyItemDto;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

readonly class BuyMonster
{
    public function __construct(
        private BuyItem $buyMonster,
    ) {
    }

    #[Route('/monster/buy/{monsterId}', methods: ['PUT'])]
    public function __invoke(Uuid $monsterId): BuyItemDto
    {
        return $this->buyMonster->buy($monsterId);
    }
}
