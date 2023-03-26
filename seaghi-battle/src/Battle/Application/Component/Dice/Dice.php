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
    /**
     * @param int $numberOfFaces The number of faces on the dice
     */
    public function __construct(
        private int $numberOfFaces,
    ) {
        if ($this->numberOfFaces < 2) {
            throw new InvalidArgumentException('The number of faces must be strictly greater than 1.');
        }
    }

    public function getNumberOfFaces(): int
    {
        return $this->numberOfFaces;
    }
}
