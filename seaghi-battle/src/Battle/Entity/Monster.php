<?php

declare(strict_types=1);

namespace App\Battle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
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
#[ORM\Table(name: 'monster')]
class Monster
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $currentHealth;

    /**
     * @var Collection<int, Effect>
     */
    #[ORM\ManyToMany(targetEntity: Effect::class)]
    private Collection $effects;

    public function __construct(
        #[ORM\Column(length: 255)]
        private string $firstName,
        #[ORM\Column(length: 255)]
        private string $lastName,
        #[ORM\Column(options: ['default' => 0])]
        private int $maxHealth,
        #[ORM\Column(options: ['default' => 0])]
        private int $defense,
        ?int $currentHealth = null,
    ) {
        $this->currentHealth = $currentHealth ?? $maxHealth;
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

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getCurrentHealth(): int
    {
        return $this->currentHealth;
    }

    public function getMaxHealth(): int
    {
        return $this->maxHealth;
    }

    public function getDefense(): int
    {
        return $this->defense;
    }

    /**
     * @return Effect[]
     */
    public function getEffects(): array
    {
        return $this->effects->toArray();
    }

    public function isAlive(): bool
    {
        return $this->currentHealth > 0;
    }

    public function isDead(): bool
    {
        return $this->currentHealth === 0;
    }

    public function applyDamage(int $amount): void
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Damage amount cannot be negative.');
        }

        $this->currentHealth = max(0, $this->currentHealth - $amount);
    }

    public function heal(int $amount): void
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Healing amount cannot be negative.');
        }

        $this->currentHealth = min($this->maxHealth, $this->currentHealth + $amount);
    }

    public function addEffect(Effect $effect): void
    {
        if (!$this->effects->contains($effect)) {
            $this->effects->add($effect);
        }
    }

    public function removeEffect(Effect $effect): void
    {
        $this->effects->removeElement($effect);
    }

    public function clearEffects(): void
    {
        $this->effects->clear();
    }

    /**
     * @param iterable<Effect> $effects
     */
    public function replaceEffects(iterable $effects): void
    {
        $this->effects->clear();
        foreach ($effects as $effect) {
            if (!$this->effects->contains($effect)) {
                $this->effects->add($effect);
            }
        }
    }
}
