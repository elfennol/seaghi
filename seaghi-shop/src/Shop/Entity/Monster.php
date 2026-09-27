<?php

declare(strict_types=1);

namespace App\Shop\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicException;
use Symfony\Component\Uid\Uuid;

/**
 * A monster.
 *
 * A monster is not a very nice creature.
 * It drools all over the place, and it makes weird grunts.
 * You can have one if you want. A monster just for you!
 * This monster will be available on the battlefield.
 */
#[ORM\Entity]
#[ORM\Table(name: 'monster')]
class Monster
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(options: ['default' => true])]
    private bool $available = true;

    #[ORM\Column(options: ['default' => false])]
    private bool $sick = false;

    public function __construct(
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private Category $category,
        #[ORM\Column]
        private int $level,
        #[ORM\Column]
        private int $price,
        #[ORM\Column(length: 255)]
        private string $firstName,
        #[ORM\Column(length: 255)]
        private string $lastName,
    ) {
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function isSick(): bool
    {
        return $this->sick;
    }

    public function isReadyToFight(): bool
    {
        return $this->available && !$this->sick && $this->level <= 10;
    }

    public function markAsSold(): void
    {
        if (!$this->isReadyToFight()) {
            throw new LogicException('Cannot sell a monster that is not ready to fight.');
        }

        $this->available = false;
    }

    public function levelUp(): void
    {
        $this->level++;
    }

    public function heal(): void
    {
        $this->sick = false;
    }

    public function makeSick(): void
    {
        $this->sick = true;
    }

    public function makeUnavailable(): void
    {
        $this->available = false;
    }
}
