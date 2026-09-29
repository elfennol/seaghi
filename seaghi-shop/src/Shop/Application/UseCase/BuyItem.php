<?php

declare(strict_types=1);

namespace App\Shop\Application\UseCase;

use App\Shop\Application\Enum\SaleRejectionReason;
use App\Shop\Port\DataContract\BuyItemDto;
use App\Shop\Port\DataContract\MonsterSoldMessage;
use App\Shop\Port\Out\MonsterRepositoryPort;
use App\Shop\Port\Out\SendMessagePort;
use App\Shop\Port\Out\TransactionPort;
use App\Shop\Port\Out\WithdrawFromAccountPort;
use Symfony\Component\Uid\Uuid;

readonly class BuyItem
{
    public function __construct(
        private MonsterRepositoryPort $monsterRepository,
        private WithdrawFromAccountPort $withdrawFromAccount,
        private SendMessagePort $sendMessage,
        private TransactionPort $transaction,
    ) {
    }

    public function buy(Uuid $monsterId): BuyItemDto
    {
        $monster = $this->monsterRepository->get($monsterId);

        if (!$monster->isReadyToFight()) {
            return new BuyItemDto($monsterId, false, SaleRejectionReason::NOT_READY_TO_FIGHT->name);
        }

        if (!$this->withdrawFromAccount->withdraw()) {
            return new BuyItemDto($monsterId, false, SaleRejectionReason::DIFFICULT_END_OF_MONTH->name);
        }

        $this->transaction->run(function () use ($monster): void {
            $monster->markAsSold();
            $this->monsterRepository->save($monster);
        });

        $this->sendMessage->send(new MonsterSoldMessage(
            $monster->getFirstName(),
            $monster->getLastName(),
            $monster->getCategory()->getCode(),
            $monster->getLevel(),
        ));

        return new BuyItemDto($monsterId, true, null);
    }
}
