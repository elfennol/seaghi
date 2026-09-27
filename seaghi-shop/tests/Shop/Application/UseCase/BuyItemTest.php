<?php

declare(strict_types=1);

namespace App\Tests\Shop\Application\UseCase;

use App\Shop\Application\UseCase\BuyItem;
use App\Shop\Entity\Category;
use App\Shop\Entity\Monster;
use App\Shop\Port\Out\MessageContract\MonsterSoldMessage;
use App\Shop\Port\Out\MonsterRepositoryPort;
use App\Shop\Port\Out\SendMessagePort;
use App\Shop\Port\Out\TransactionPort;
use App\Shop\Port\Out\WithdrawFromAccountPort;
use App\Tests\Shop\Application\EntityIdSetterTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Uid\Uuid;

class BuyItemTest extends TestCase
{
    use EntityIdSetterTrait;

    private BuyItem $buyItem;
    private MonsterRepositoryPort $monsterRepository;
    private WithdrawFromAccountPort $withdrawFromAccount;
    private SendMessagePort $sendMessage;
    private TransactionPort $transaction;

    protected function setUp(): void
    {
        $this->monsterRepository = $this->createStub(MonsterRepositoryPort::class);
        $this->withdrawFromAccount = $this->createMock(WithdrawFromAccountPort::class);
        $this->sendMessage = $this->createMock(SendMessagePort::class);
        $this->transaction = $this->createStub(TransactionPort::class);
        $this->transaction->method('run')->willReturnCallback(static fn (callable $operation): mixed => $operation());

        $this->buyItem = new BuyItem(
            $this->monsterRepository,
            $this->withdrawFromAccount,
            $this->sendMessage,
            $this->transaction,
        );
    }

    /**
     * @dataProvider notReadyToFightProvider
     */
    #[DataProvider('notReadyToFightProvider')]
    public function testNotReadyToFight(bool $isAvailable, bool $isSick, int $level): void
    {
        $monsterEntity = $this->buildMonster($isAvailable, $isSick, $level);
        $this->monsterRepository->method('get')->willReturn($monsterEntity);
        $this->withdrawFromAccount->expects($this->never())->method('withDraw');
        $this->sendMessage->expects($this->never())->method('send');

        $buyItemDto = $this->buyItem->buy(Uuid::fromString('11111111-1111-1111-1111-111111111111'));

        $this::assertFalse($buyItemDto->canBuy);
    }

    public function testWithDraw(): void
    {
        $monsterEntity = $this->buildMonster(true, false, 2);
        $this->monsterRepository->method('get')->willReturn($monsterEntity);
        $this->withdrawFromAccount->expects($this->once())->method('withDraw')->willReturn(false);
        $this->sendMessage->expects($this->never())->method('send');

        $buyDto = $this->buyItem->buy(Uuid::fromString('11111111-1111-1111-1111-111111111111'));

        $this::assertFalse($buyDto->canBuy);
    }

    public function testMarkAsUnavailable(): void
    {
        $monsterEntity = $this->buildMonster(true, false, 2);
        $this->monsterRepository->method('get')->willReturn($monsterEntity);
        $this->withdrawFromAccount->expects($this->once())->method('withDraw')->willReturn(true);
        $this->sendMessage->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(MonsterSoldMessage::class));

        $buyDto = $this->buyItem->buy(Uuid::fromString('11111111-1111-1111-1111-111111111111'));

        $this::assertFalse($monsterEntity->isAvailable());
        $this::assertTrue($buyDto->canBuy);
    }

    public function testDoNotSendMessageWhenTransactionFails(): void
    {
        $monsterEntity = $this->buildMonster(true, false, 2);
        $this->monsterRepository->method('get')->willReturn($monsterEntity);
        $this->withdrawFromAccount->expects($this->once())->method('withDraw')->willReturn(true);

        $failingTransaction = $this->createStub(TransactionPort::class);
        $failingTransaction->method('run')->willThrowException(new RuntimeException('DB error'));

        $this->sendMessage->expects($this->never())->method('send');

        $buyItem = new BuyItem(
            $this->monsterRepository,
            $this->withdrawFromAccount,
            $this->sendMessage,
            $failingTransaction,
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DB error');

        $buyItem->buy(Uuid::fromString('11111111-1111-1111-1111-111111111111'));
    }

    public static function notReadyToFightProvider(): array
    {
        return [
            [false, false, 2],
            [true, true, 2],
            [true, false, 11],
        ];
    }

    private function buildMonster(bool $isAvailable, bool $isSick, int $level): Monster
    {
        $category = new Category();
        $category->setCode(Category::CODE_SHAPESHIFTER_CHICKEN);

        $monsterEntity = new Monster(
            $category,
            $level,
            10,
            'first_name',
            'last_name',
        );
        if (!$isAvailable) {
            $monsterEntity->makeUnavailable();
        }
        if ($isSick) {
            $monsterEntity->makeSick();
        }
        $this->setEntityId($monsterEntity, Uuid::fromString('11111111-1111-1111-1111-111111111111'));

        return $monsterEntity;
    }
}
