<?php

declare(strict_types=1);

namespace App\Battle\Application\Component\Dice;

use InvalidArgumentException;

/**
 * A die is an object exhibiting faces.
 * Each face contains a single number.
 * Each number is an integer.
 * The numbers are different on each face.
 * The minimum number of faces is 2.
 * The smaller number is 1.
 * The larger number is the number of faces.
 */
readonly class Dice
{
    private function __construct(
        private int $numberOfFaces,
    ) {

    }

    /**
     * @throws InvalidArgumentException The number of faces must be strictly greater than 1
     */
    public static function create(int $numberOfFaces): self
    {
        if ($numberOfFaces < 2) {
            throw new InvalidArgumentException('The number of faces must be strictly greater than 1.');
        }

        return new self($numberOfFaces);
    }

    public function getNumberOfFaces(): int
    {
        return $this->numberOfFaces;
    }
}
