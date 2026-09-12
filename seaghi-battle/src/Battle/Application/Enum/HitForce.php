<?php

declare(strict_types=1);

namespace App\Battle\Application\Enum;

/**
 * The types of the hit.
 */
enum HitForce
{
    case NORMAL;
    case CRITICAL;
}
