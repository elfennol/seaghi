<?php

declare(strict_types=1);

namespace App\Battle\Application\Enum;

/**
 * The categories of a monster.
 */
enum Category: string
{
    case WILD_SQUIRREL = 'wild_squirrel';
    case SHAPESHIFTER_CHICKEN = 'shapeshifter_chicken';
    case LOLCAT = 'lol_cat';
    case CARIBOU_AVENGER = 'caribou_avenger';
}
