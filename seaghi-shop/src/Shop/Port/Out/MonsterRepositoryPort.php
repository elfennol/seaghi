<?php

declare(strict_types=1);

namespace App\Shop\Port\Out;

use App\Shop\Entity\Monster;
use App\Shop\Port\Out\Exception\MonsterNotFoundException;
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
