<?php

declare(strict_types=1);

namespace App\Tests\Shop\Application\UseCase;

use App\Shop\Application\UseCase\ListItem;
use App\Shop\Port\DataContract\SearchMonsterDto;
use App\Shop\Port\Out\SearchMonsterPort;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ListMonsterTest extends TestCase
{
    private SearchMonsterPort $searchMonster;
    private ListItem $listItem;

    protected function setUp(): void
    {
        $this->searchMonster = $this->createStub(SearchMonsterPort::class);
        $this->listItem = new ListItem($this->searchMonster);
    }

    public function testList(): void
    {
        $dto = new SearchMonsterDto(
            Uuid::fromString('11111111-1111-1111-1111-111111111111'),
            'lol_cat',
            3,
            150,
            'Felix',
            'The Cat',
            true,
            false,
        );

        $mockSearch = $this->createMock(SearchMonsterPort::class);
        $mockSearch->expects($this->once())
            ->method('search')
            ->with(1, 10)
            ->willReturn([$dto]);

        $listItem = new ListItem($mockSearch);
        $result = $listItem->list(1, 10);

        $this::assertSame([$dto], $result);
    }

    #[DataProvider('invalidLevelProvider')]
    public function testInvalidLevels(int $levelMin, int $levelMax): void
    {
        $this->expectException(InvalidArgumentException::class);
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
}
