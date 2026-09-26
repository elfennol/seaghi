<?php

declare(strict_types=1);

namespace App\Battle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * An effect can be attached to the monster during battle.
 */
#[ORM\Entity]
#[ORM\Table(name: "effect")]
class Effect
{
    public const string CODE_SERIOUS_INJURY = 'serious_injury';
    public const string CODE_BADASS = 'badass';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private string $code;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
