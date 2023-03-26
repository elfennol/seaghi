<?php

declare(strict_types=1);

namespace App\Shop\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A monster.
 *
 * A monster is not a very nice creature.
 * It drools all over the place and it makes weird grunts.
 * You can have one if you want. A monster just for you!
 * This monster will be available on the battlefield.
 *
 **/
#[ORM\Entity]
#[ORM\Table(name: "monster")]
class Monster
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(options: ["default" => true])]
    private ?bool $available = true;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $sick = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function isSick(): ?bool
    {
        return $this->sick;
    }

    public function setSick(bool $sick): self
    {
        $this->sick = $sick;

        return $this;
    }
}
