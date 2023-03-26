<?php

declare(strict_types=1);

namespace App\Battle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * An effect can be attached to the monster during battle.
 */
#[ORM\Entity]
#[ORM\Table(name: "effect")]
class Effect
{
    public const CODE_SERIOUS_INJURY = 'serious_injury';
    public const CODE_BADASS = 'badass';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $code = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
