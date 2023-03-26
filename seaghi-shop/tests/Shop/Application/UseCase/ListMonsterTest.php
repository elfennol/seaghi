<?php

declare(strict_types=1);

namespace App\Tests\Shop\Application\UseCase;

use App\Shop\Application\UseCase\ListItem;
use App\Shop\Entity\Category;
use App\Shop\Entity\Monster;
use App\Shop\Port\Out\SearchMonsterPort;
use App\Tests\Shop\Application\EntityIdSetterTrait;
use PHPUnit\Framework\TestCase;

/**
 * Feature: List monster
 */
class ListMonsterTest extends TestCase
{
    use EntityIdSetterTrait;

    private ListItem $listItem;

    private SearchMonsterPort $searchMonster;

    /**
     * Given some monsters in the shop
     * When the player lists these monsters without a min level
     * Then the minimum level filter is set to 1
     */
    public function testEmptyLevelMin(): void
    {
        $this->searchMonster->expects($this->once())
            ->method('search')
            ->willReturn($this->buildMonsters())
            ->with(1);

        $this->listItem->list(null, 4);
    }

    /**
     * Given some monsters in the shop
     * When the player lists these monsters without a max level
     * Then the maximum level filter is set to 10
     */
    public function testEmptyLevelMax(): void
    {
        $this->searchMonster->expects($this->once())
            ->method('search')
            ->willReturn($this->buildMonsters())
            ->with(1, 10);

        $this->listItem->list(1, 10);
    }

    /**
     * Given some monsters in the shop
     * When the player lists these monsters with a max level filter greater than 10
     * Then the maximum level filter is set to 10
     */
    public function testLevelMaxTooHigh(): void
    {
        $this->searchMonster->expects($this->once())
            ->method('search')
            ->willReturn($this->buildMonsters())
            ->with(1, 10);

        $this->listItem->list(1, 11);
    }

    protected function setUp(): void
    {
        $this->searchMonster = $this->createMock(SearchMonsterPort::class);

        $this->listItem = new ListItem($this->searchMonster);
    }

    /**
     * @return Monster[]
     */
    private function buildMonsters(): array
    {
        $monsterEntityChicken = new Monster();
        $monsterEntityChicken->setLevel(2);
        $monsterEntityChicken->setPrice(10);
        $monsterEntityChicken->setFirstName('first_name_chicken');
        $monsterEntityChicken->setLastName('last_name_chicken');
        $monsterEntityChicken->setAvailable(true);
        $monsterEntityChicken->setSick(false);
        $category = new Category();
        $category->setCode(Category::CODE_SHAPESHIFTER_CHICKEN);
        $monsterEntityChicken->setCategory($category);
        $this->setEntityId($monsterEntityChicken, 1);

        $monsterEntityLolCat = new Monster();
        $monsterEntityLolCat->setLevel(3);
        $monsterEntityLolCat->setPrice(11);
        $monsterEntityLolCat->setFirstName('first_name_cat');
        $monsterEntityLolCat->setLastName('last_name_cat');
        $monsterEntityLolCat->setAvailable(true);
        $monsterEntityLolCat->setSick(false);
        $category = new Category();
        $category->setCode(Category::CODE_LOLCAT);
        $monsterEntityLolCat->setCategory($category);
        $this->setEntityId($monsterEntityLolCat, 2);


        return [$monsterEntityChicken, $monsterEntityLolCat];
    }
}
