<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\Persistence;

use App\Shop\Port\Out\IdentityGeneratorPort;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;

readonly class IdentityGenerator implements IdentityGeneratorPort
{
    public function __construct(
        private UuidFactory $uuidFactory,
    ) {
    }

    public function generate(): Uuid
    {
        return $this->uuidFactory->create();
    }
}
