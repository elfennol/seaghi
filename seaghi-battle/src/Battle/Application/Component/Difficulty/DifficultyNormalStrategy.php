<?php

declare(strict_types=1);

namespace App\Battle\Application\Component\Difficulty;

use App\Battle\Application\Enum\Category;

class DifficultyNormalStrategy implements DifficultyInterface
{
    public function buildDefense(Category $category): int
    {
        return match ($category) {
            Category::WILD_SQUIRREL => 5,
            Category::SHAPESHIFTER_CHICKEN => 7,
            Category::LOLCAT => 10,
            Category::CARIBOU_AVENGER => 15,
        };
    }

    public function buildMaxHealth(Category $category, int $level): int
    {
        return match ($category) {
            Category::WILD_SQUIRREL => 20 * $level,
            Category::SHAPESHIFTER_CHICKEN => 25 * $level,
            Category::LOLCAT => 30 * $level,
            Category::CARIBOU_AVENGER => 40 * $level,
        };
    }
}
