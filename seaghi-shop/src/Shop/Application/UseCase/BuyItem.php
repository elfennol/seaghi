<?php

declare(strict_types=1);

namespace App\Shop\Application\UseCase;

use App\Shop\Application\Component\CheckMonsterStatus;
use App\Shop\Application\Enum\SaleRejectionReason;
use App\Shop\Port\In\DataContract\BuyItemDto;
use App\Shop\Port\Out\MessageContract\MonsterSoldMessage;
use App\Shop\Entity\Monster;
use App\Shop\Port\In\BuyItemPort;
use App\Shop\Port\Out\FindEntityPort;
use App\Shop\Port\Out\PersistEntityPort;
use App\Shop\Port\Out\SendMessagePort;
use App\Shop\Port\Out\WithdrawFromAccountPort;

readonly class BuyItem implements BuyItemPort
{
    public function __construct(
        private FindEntityPort $findEntity,
        private PersistEntityPort $persistEntity,
        private WithdrawFromAccountPort $withdrawFromAccount,
        private SendMessagePort $sendMessage,
        private CheckMonsterStatus $checkMonsterStatus
    ) {
    }

    public function buy(int $monsterId): BuyItemDto
    {
        /** @var Monster $monsterEntity */
        $monsterEntity = $this->findEntity->find(Monster::class, $monsterId);

        $canBuy = true;
        $rejectionReason = null;
        if (false === $this->checkMonsterStatus->isReadyToFight($monsterEntity)) {
            $canBuy = false;
            $rejectionReason = SaleRejectionReason::NOT_READY_TO_FIGHT;
        }
        if (!$this->withdrawFromAccount->withdraw()) {
            $canBuy = false;
            $rejectionReason = SaleRejectionReason::DIFFICULT_END_OF_MONTH;
        }

        if (true === $canBuy) {
            $monsterEntity->setAvailable(false);
            $this->persistEntity->persist($monsterEntity);
            $this->sendMessage->send(new MonsterSoldMessage(
                $monsterEntity->getFirstName(),
                $monsterEntity->getLastName(),
                $monsterEntity->getCategory()->getCode(),
                $monsterEntity->getLevel(),
            ));
        }

        return new BuyItemDto(
            $monsterEntity->getId(),
            $canBuy,
            $rejectionReason->value ?? null
        );
    }
}
