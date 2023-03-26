<?php

declare(strict_types=1);

namespace App\Battle\Application\Component\Difficulty;

use App\Battle\Application\Enum\Category;

/**
 * Build some characteristics for the monster regarding the difficulty.
 */
interface DifficultyInterface
{
    /**
     * Build the defense from the $category.
     */
    public function buildDefense(Category $category): int;

    /**
     * Build the max health from the $category and $level.
     */
    public function buildMaxHealth(Category $category, int $level): int;
}
