<?php

declare(strict_types=1);

namespace App\Shop\Port\Out;

use Symfony\Component\Uid\Uuid;

interface IdentityGeneratorPort
{
    public function generate(): Uuid;
}
