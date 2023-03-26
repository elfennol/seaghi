<?php

declare(strict_types=1);

namespace App\Battle\Application\Component;

use App\Battle\Entity\Monster;

/**
 * Format the name of a monster.
 */
class FormatName
{
    public function getFullName(Monster $monster): string
    {
        return $monster->getFirstName() . ' ' . $monster->getLastName();
    }
}
