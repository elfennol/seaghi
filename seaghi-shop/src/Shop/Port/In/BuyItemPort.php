<?php

declare(strict_types=1);

namespace App\Shop\Port\In;

use App\Shop\Port\In\DataContract\BuyItemDto;
use Symfony\Component\Uid\Uuid;

/**
 * Buy the monster of your dreams!
 *
 * You can buy a monster if this monster is ready to fight and if you have enough GP.
 */
interface BuyItemPort
{
    public function buy(Uuid $monsterId): BuyItemDto;
}
