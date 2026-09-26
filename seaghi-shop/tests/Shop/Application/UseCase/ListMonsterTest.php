<?php

declare(strict_types=1);

namespace App\Tests\Shop\Application\UseCase;

use App\Shop\Application\UseCase\ListItem;
use App\Shop\Entity\Category;
use App\Shop\Entity\Monster;
use App\Shop\Port\In\DataContract\SearchMonsterDto;
use App\Shop\Port\Out\SearchMonsterPort;
use App\Tests\Shop\Application\EntityIdSetterTrait;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ListMonsterTest extends TestCase
{
    use EntityIdSetterTrait;

    private SearchMonsterPort $searchMonster;
    private ListItem $listItem;

    protected function setUp(): void
    {
        $this->searchMonster = $this->createStub(SearchMonsterPort::class);
        $this->listItem = new ListItem($this->searchMonster);
    }

    /**
     * Given monsters found in the shop
     * When the player lists monsters within valid level range
     * Then it returns the mapped SearchMonsterDto collection
     */
    public function testList(): void
    {
        $monster = $this->buildMonster(Uuid::fromString('11111111-1111-1111-1111-111111111111'), 'lol_cat', 3, 150, 'Felix', 'The Cat', true, false);

        $mockSearch = $this->createMock(SearchMonsterPort::class);
        $mockSearch->expects($this->once())
            ->method('search')
            ->with(1, 10)
            ->willReturn([$monster]);

        $listItem = new ListItem($mockSearch);
        $result = $listItem->list(1, 10);

        $this::assertIsArray($result);
        $this::assertCount(1, $result);
        $this::assertInstanceOf(SearchMonsterDto::class, $result[0]);
        $this::assertSame(Uuid::fromString('11111111-1111-1111-1111-111111111111')->toRfc4122(), $result[0]->id->toRfc4122());
        $this::assertSame('lol_cat', $result[0]->categoryCode);
        $this::assertSame(3, $result[0]->level);
        $this::assertSame(150, $result[0]->price);
        $this::assertSame('Felix', $result[0]->firstName);
        $this::assertSame('The Cat', $result[0]->lastName);
        $this::assertTrue($result[0]->available);
        $this::assertFalse($result[0]->sick);
    }

    /**
     * Given an invalid level range outside 1-10
     * When the player lists monsters
     * Then an exception is thrown
     */
    #[DataProvider('invalidLevelProvider')]
    public function testInvalidLevels(int $levelMin, int $levelMax): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Allowed levels are between 1 and 10.');

        $this->listItem->list($levelMin, $levelMax);
    }

    /**
     * @return array<string, array{int, int}>
     */
    public static function invalidLevelProvider(): array
    {
        return [
            'min below 1' => [0, 5],
            'min above 10' => [11, 5],
            'max below 1' => [1, 0],
            'max above 10' => [1, 11],
        ];
    }

    private function buildMonster(
        Uuid $monsterId,
        string $categoryCode,
        int $level,
        int $price,
        string $firstName,
        string $lastName,
        bool $available,
        bool $sick,
    ): Monster {
        $category = new Category();
        $category->setCode($categoryCode);

        $monster = new Monster();
        $monster->setCategory($category);
        $monster->setLevel($level);
        $monster->setPrice($price);
        $monster->setFirstName($firstName);
        $monster->setLastName($lastName);
        $monster->setAvailable($available);
        $monster->setSick($sick);
        $this->setEntityId($monster, $monsterId);

        return $monster;
    }
}
