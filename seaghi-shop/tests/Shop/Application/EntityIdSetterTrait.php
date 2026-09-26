<?php

declare(strict_types=1);

namespace App\Tests\Shop\Application;

use Symfony\Component\Uid\Uuid;

trait EntityIdSetterTrait
{
    /**
     * Set the id of the entity (an entity has no setId).
     */
    private function setEntityId(object $entity, Uuid $id)
    {
        (function () use ($id): void {
            $this->id = $id;
        })->bindTo($entity, $entity::class)();
    }
}
