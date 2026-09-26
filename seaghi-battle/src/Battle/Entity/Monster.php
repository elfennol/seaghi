<?php

declare(strict_types=1);

namespace App\Battle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * A monster.
 *
 * A monster is a fierce fighter.
 * You can hit it if you want. Yes you have the right.
 * You can also heal it. Just because you want to keep hitting him even longer.
 * A monster can dodge an attack with a defence number ∈ [0,19].
 * A monster have a name (a very scary).
 */
#[ORM\Entity]
#[ORM\Table(name: "monster")]
class Monster
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private string $firstName;

    #[ORM\Column(length: 255)]
    private string $lastName;

    #[ORM\Column(options: ["default" => 0])]
    private int $currentHealth;

    #[ORM\Column(options: ["default" => 0])]
    private int $maxHealth;

    #[ORM\Column(options: ["default" => 0])]
    private int $defense;

    /**
     * @var Collection<int, Effect>
     */
    #[ORM\ManyToMany(targetEntity: Effect::class)]
    private Collection $effects;

    public function __construct()
    {
        $this->effects = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCurrentHealth(): int
    {
        return $this->currentHealth;
    }

    public function setCurrentHealth(int $currentHealth): self
    {
        $this->currentHealth = $currentHealth;

        return $this;
    }

    public function getMaxHealth(): int
    {
        return $this->maxHealth;
    }

    public function setMaxHealth(int $maxHealth): self
    {
        $this->maxHealth = $maxHealth;

        return $this;
    }

    public function getDefense(): int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): self
    {
        $this->defense = $defense;

        return $this;
    }

    /**
     * @return Collection<int, Effect>
     */
    public function getEffects(): Collection
    {
        return $this->effects;
    }

    public function addEffect(Effect $effect): self
    {
        if (!$this->effects->contains($effect)) {
            $this->effects->add($effect);
        }

        return $this;
    }

    /**
     * @param Collection<int, Effect> $effects
     */
    public function setEffects(Collection $effects): void
    {
        $this->effects = $effects;
    }

    public function removeEffect(Effect $effect): self
    {
        $this->effects->removeElement($effect);

        return $this;
    }
}
