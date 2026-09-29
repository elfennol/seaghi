<?php

declare(strict_types=1);

namespace App\Tests\Shop\Entity;

use App\Shop\Entity\Category;
use App\Shop\Entity\Monster;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

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
        $id = Uuid::v7();
        $monster = new Monster($id, $this->category, 3, 50, 'Gargamel', 'Cat');

        $this::assertSame($id, $monster->getId());
        $this::assertSame($this->category, $monster->getCategory());
        $this::assertSame(3, $monster->getLevel());
        $this::assertSame(50, $monster->getPrice());
        $this::assertSame('Gargamel', $monster->getFirstName());
        $this::assertSame('Cat', $monster->getLastName());
        $this::assertTrue($monster->isAvailable());
        $this::assertFalse($monster->isSick());
    }

    public function testNegativePriceThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Price must be greater than or equal to 0.');

        new Monster(Uuid::v7(), $this->category, 5, -1, 'Fang', 'Beast');
    }

    #[DataProvider('invalidLevelProvider')]
    public function testInvalidLevelThrows(int $level): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Level must be between 1 and 10.');

        new Monster(Uuid::v7(), $this->category, $level, 50, 'Fang', 'Beast');
    }

    public static function invalidLevelProvider(): array
    {
        return [
            'level zero' => [0],
            'negative level' => [-1],
            'level 11' => [11],
        ];
    }

    public function testIsReadyToFight(): void
    {
        $monster = new Monster(Uuid::v7(), $this->category, 5, 100, 'Fang', 'Beast');

        $this::assertTrue($monster->isReadyToFight());
    }

    public function testNotReadyWhenUnavailable(): void
    {
        $monster = new Monster(Uuid::v7(), $this->category, 5, 100, 'Fang', 'Beast');
        $monster->makeUnavailable();

        $this::assertFalse($monster->isReadyToFight());
    }

    public function testNotReadyWhenSick(): void
    {
        $monster = new Monster(Uuid::v7(), $this->category, 5, 100, 'Fang', 'Beast');
        $monster->makeSick();

        $this::assertFalse($monster->isReadyToFight());
    }

    public function testNotReadyWhenLevelTooHigh(): void
    {
        $monster = new Monster(Uuid::v7(), $this->category, 10, 100, 'Fang', 'Beast');
        $monster->levelUp();

        $this::assertFalse($monster->isReadyToFight());
    }

    public function testMarkAsSold(): void
    {
        $monster = new Monster(Uuid::v7(), $this->category, 5, 100, 'Fang', 'Beast');

        $monster->markAsSold();

        $this::assertFalse($monster->isAvailable());
    }

    public function testMarkAsSoldFailsWhenNotReady(): void
    {
        $monster = new Monster(Uuid::v7(), $this->category, 5, 100, 'Fang', 'Beast');
        $monster->makeUnavailable();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cannot sell a monster that is not ready to fight.');

        $monster->markAsSold();
    }

    public function testLevelUp(): void
    {
        $monster = new Monster(Uuid::v7(), $this->category, 2, 50, 'Pip', 'Squeak');

        $monster->levelUp();

        $this::assertSame(3, $monster->getLevel());
    }

    public function testHeal(): void
    {
        $monster = new Monster(Uuid::v7(), $this->category, 2, 50, 'Pip', 'Squeak');
        $monster->makeSick();

        $monster->heal();

        $this::assertFalse($monster->isSick());
    }

    public function testMakeSick(): void
    {
        $monster = new Monster(Uuid::v7(), $this->category, 2, 50, 'Pip', 'Squeak');

        $monster->makeSick();

        $this::assertTrue($monster->isSick());
    }
}
