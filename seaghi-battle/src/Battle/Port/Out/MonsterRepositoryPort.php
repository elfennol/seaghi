<?php

declare(strict_types=1);

namespace App\Battle\Port\Out;

use App\Battle\Entity\Monster;
use App\Battle\Port\Out\Exception\MonsterNotFoundException;
use Symfony\Component\Uid\Uuid;

/**
 * Monster repository port.
 */
interface MonsterRepositoryPort
{
    /**
     * @throws MonsterNotFoundException
     */
    public function get(Uuid $id): Monster;

    public function find(Uuid $id): ?Monster;

    public function save(Monster $monster): void;
}
