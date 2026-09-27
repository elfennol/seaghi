<?php

declare(strict_types=1);

namespace App\Tests\Shop\Entity;

use App\Shop\Entity\Category;
use App\Shop\Entity\Monster;
use LogicException;
use PHPUnit\Framework\TestCase;

class MonsterTest extends TestCase
{
    private Category $category;

    protected function setUp(): void
    {
        $this->category = new Category();
        $this->category->setCode(Category::CODE_LOLCAT);
    }

    public function testGetters(): void
    {
        $monster = new Monster($this->category, 3, 50, 'Gargamel', 'Cat');

        $this::assertNull($monster->getId());
        $this::assertSame($this->category, $monster->getCategory());
        $this::assertSame(3, $monster->getLevel());
        $this::assertSame(50, $monster->getPrice());
        $this::assertSame('Gargamel', $monster->getFirstName());
        $this::assertSame('Cat', $monster->getLastName());
        $this::assertTrue($monster->isAvailable());
        $this::assertFalse($monster->isSick());
    }

    public function testIsReadyToFight(): void
    {
        $monster = new Monster($this->category, 5, 100, 'Fang', 'Beast');

        $this::assertTrue($monster->isReadyToFight());
    }

    public function testNotReadyWhenUnavailable(): void
    {
        $monster = new Monster($this->category, 5, 100, 'Fang', 'Beast');
        $monster->makeUnavailable();

        $this::assertFalse($monster->isReadyToFight());
    }

    public function testNotReadyWhenSick(): void
    {
        $monster = new Monster($this->category, 5, 100, 'Fang', 'Beast');
        $monster->makeSick();

        $this::assertFalse($monster->isReadyToFight());
    }

    public function testNotReadyWhenLevelTooHigh(): void
    {
        $monster = new Monster($this->category, 11, 100, 'Fang', 'Beast');

        $this::assertFalse($monster->isReadyToFight());
    }

    public function testMarkAsSold(): void
    {
        $monster = new Monster($this->category, 5, 100, 'Fang', 'Beast');

        $monster->markAsSold();

        $this::assertFalse($monster->isAvailable());
    }

    public function testMarkAsSoldFailsWhenNotReady(): void
    {
        $monster = new Monster($this->category, 5, 100, 'Fang', 'Beast');
        $monster->makeUnavailable();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cannot sell a monster that is not ready to fight.');

        $monster->markAsSold();
    }

    public function testLevelUp(): void
    {
        $monster = new Monster($this->category, 2, 50, 'Pip', 'Squeak');

        $monster->levelUp();

        $this::assertSame(3, $monster->getLevel());
    }

    public function testHeal(): void
    {
        $monster = new Monster($this->category, 2, 50, 'Pip', 'Squeak');
        $monster->makeSick();

        $monster->heal();

        $this::assertFalse($monster->isSick());
    }

    public function testMakeSick(): void
    {
        $monster = new Monster($this->category, 2, 50, 'Pip', 'Squeak');

        $monster->makeSick();

        $this::assertTrue($monster->isSick());
    }
}
