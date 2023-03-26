<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\Messenger;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * The message data holder of a sold monster.
 */
readonly class MonsterSoldMessage
{
    /**
     * @Assert\NotBlank
     */
    public string $firstName;

    /**
     * @Assert\NotNull
     */
    public string $lastName;

    /**
     * @Assert\NotBlank
     */
    public string $categoryCode;

    /**
     * @Assert\Positive
     */
    public int $level;

    public function __construct(string $firstName, string $lastName, string $categoryCode, int $level)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->categoryCode = $categoryCode;
        $this->level = $level;
    }
}
