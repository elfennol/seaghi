<?php

declare(strict_types=1);

namespace App\Tests\Shop\Entity;

use App\Shop\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $category = new Category();

        $this::assertNull($category->getId());

        $category->setCode(Category::CODE_WILD_SQUIRREL);
        $this::assertSame(Category::CODE_WILD_SQUIRREL, $category->getCode());
    }
}
