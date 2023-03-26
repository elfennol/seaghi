<?php

declare(strict_types=1);

namespace App\Tests\Battle\Application;

trait EntityIdSetterTrait
{
    /**
     * Set the id of the entity (an entity has no setId).
     */
    private function setEntityId(object $entity, int $id): void
    {
        (function () use ($id) {
            $this->id = $id;
        })->bindTo($entity, get_class($entity))();
    }
}
