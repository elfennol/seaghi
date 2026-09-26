<?php

declare(strict_types=1);

namespace App\Shop\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * A monster category.
 *
 * A category is identified by a code.
 * Some categories are very scary!
 *
 **/
#[ORM\Entity]
#[ORM\Table(name: "category")]
class Category
{
    public const CODE_WILD_SQUIRREL = 'wild_squirrel';
    public const CODE_SHAPESHIFTER_CHICKEN = 'shapeshifter_chicken';
    public const CODE_LOLCAT = 'lol_cat';
    public const CODE_CARIBOU_AVENGER = 'caribou_avenger';

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
