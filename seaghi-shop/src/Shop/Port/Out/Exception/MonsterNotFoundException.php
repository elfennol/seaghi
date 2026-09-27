<?php

declare(strict_types=1);

namespace App\Shop\Port\Out\Exception;

use RuntimeException;
use Symfony\Component\Uid\Uuid;

class MonsterNotFoundException extends RuntimeException
{
    public static function forId(Uuid $id): self
    {
        return new self(sprintf('Monster with ID "%s" not found.', $id->toRfc4122()));
    }
}
