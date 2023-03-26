<?php

declare(strict_types=1);

namespace App\Tests\Shop\Application\UseCase;

use App\Shop\Application\Component\CheckMonsterStatus;
use App\Shop\Port\Out\MessageContract\MonsterSoldMessage;
use App\Shop\Application\UseCase\BuyItem;
use App\Shop\Entity\Category;
use App\Shop\Entity\Monster;
use App\Shop\Port\Out\FindEntityPort;
use App\Shop\Port\Out\PersistEntityPort;
use App\Shop\Port\Out\SendMessagePort;
use App\Shop\Port\Out\WithdrawFromAccountPort;
use App\Tests\Shop\Application\EntityIdSetterTrait;
use PHPUnit\Framework\TestCase;

class BuyItemTest extends TestCase
{
    use EntityIdSetterTrait;

    private BuyItem $buyItem;

    private FindEntityPort $findEntity;
    private PersistEntityPort $persistEntity;
    private WithdrawFromAccountPort $withdrawFromAccount;
    private SendMessagePort $sendMessage;
    private CheckMonsterStatus $checkMonsterStatus;

    /**
     * Given a monster not ready to fight in the shop
     * When the player buys this monster with enough GP
     * Then the shop refuse to sell this monster
     *
     * @dataProvider notReadyToFightProvider
     */
    public function testNotReadyToFight(bool $isAvailable, bool $isSick, int $level): void
    {
        $monsterEntity = $this->buildMonster($isAvailable, $isSick, $level);
        $this->findEntity->method('find')->willReturn($monsterEntity);
        $this->withdrawFromAccount->method('withDraw')->willReturn(true);

        $buyItemDto = $this->buyItem->buy(1);

        $this::assertFalse($buyItemDto->canBuy);
    }

    /**
     * Given a monster ready to fight in the shop
     * When the player buys this monster without enough GP
     * Then the shop refuse to sell this monster
     */
    public function testWithDraw(): void
    {
        $monsterEntity = $this->buildMonster(true, false, 2);
        $this->findEntity->method('find')->willReturn($monsterEntity);
        $this->withdrawFromAccount->method('withDraw')->willReturn(false);

        $buyDto = $this->buyItem->buy(1);

        $this::assertFalse($buyDto->canBuy);
    }

    /**
     * Given a monster ready to fight in the shop
     * When the player buys this monster with enough GP
     * Then the shop accept to sell and mark the monster as unavailable and send the message MonsterSoldMessage
     */
    public function testMarkAsUnavailable(): void
    {
        $monsterEntity = $this->buildMonster(true, false, 2);
        $this->findEntity->method('find')->willReturn($monsterEntity);
        $this->withdrawFromAccount->method('withDraw')->willReturn(true);
        $this->sendMessage->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(MonsterSoldMessage::class));

        $buyDto = $this->buyItem->buy(1);

        $this::assertFalse($monsterEntity->isAvailable());
        $this::assertTrue($buyDto->canBuy);
    }

    /**
     * [[available, sick, level], ...]
     */
    public function notReadyToFightProvider(): array
    {
        return [
            [false, false, 2],
            [true, true, 2],
            [true, false, 11],
        ];
    }

    protected function setUp(): void
    {
        $this->findEntity = $this->createMock(FindEntityPort::class);
        $this->persistEntity = $this->createMock(PersistEntityPort::class);
        $this->withdrawFromAccount = $this->createMock(WithdrawFromAccountPort::class);
        $this->sendMessage = $this->createMock(SendMessagePort::class);
        $this->checkMonsterStatus = new CheckMonsterStatus();

        $this->buyItem = new BuyItem(
            $this->findEntity,
            $this->persistEntity,
            $this->withdrawFromAccount,
            $this->sendMessage,
            $this->checkMonsterStatus,
        );
    }

    private function buildMonster(bool $isAvailable, bool $isSick, int $level): Monster
    {
        $monsterEntity = new Monster();
        $monsterEntity->setLevel($level);
        $monsterEntity->setPrice(10);
        $monsterEntity->setFirstName('first_name');
        $monsterEntity->setLastName('last_name');
        $monsterEntity->setAvailable($isAvailable);
        $monsterEntity->setSick($isSick);
        $category = new Category();
        $category->setCode(Category::CODE_SHAPESHIFTER_CHICKEN);
        $monsterEntity->setCategory($category);
        $this->setEntityId($monsterEntity, 1);

        return $monsterEntity;
    }
}
