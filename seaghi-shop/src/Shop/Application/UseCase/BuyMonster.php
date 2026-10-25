<?php

declare(strict_types=1);

namespace App\Shop\Application\UseCase;

use App\Shop\Port\DataContract\BuyMonsterDto;
use App\Shop\Port\DataContract\MonsterSoldMessage;
use App\Shop\Port\DataContract\SaleRejectionReason;
use App\Shop\Port\Out\MonsterRepositoryPort;
use App\Shop\Port\Out\SendMessagePort;
use App\Shop\Port\Out\TransactionPort;
use App\Shop\Port\Out\WithdrawFromAccountPort;
use Symfony\Component\Uid\Uuid;

readonly class BuyMonster
{
    public function __construct(
        private MonsterRepositoryPort $monsterRepository,
        private WithdrawFromAccountPort $withdrawFromAccount,
        private SendMessagePort $sendMessage,
        private TransactionPort $transaction,
    ) {
    }

    public function execute(Uuid $monsterId): BuyMonsterDto
    {
        $monster = $this->monsterRepository->get($monsterId);

        if (!$monster->isReadyToFight()) {
            return BuyMonsterDto::rejected($monsterId, SaleRejectionReason::NOT_READY_TO_FIGHT);
        }

        if (!$this->withdrawFromAccount->withdraw()) {
            return BuyMonsterDto::rejected($monsterId, SaleRejectionReason::DIFFICULT_END_OF_MONTH);
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

        return BuyMonsterDto::accepted($monsterId);
    }
}
