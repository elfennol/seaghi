<?php

declare(strict_types=1);

namespace App\Battle\Infrastructure\Messenger;

use App\Battle\Port\In\SpawnMonsterPort;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Handle the message MonsterSoldMessage.
 */
#[AsMessageHandler]
readonly class MonsterSoldMessageHandler
{
    public function __construct(
        private MessageValidatorInterface $validator,
        private SpawnMonsterPort $spawnMonster,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(MonsterSoldMessage $message): void
    {
        try {
            $this->validator->assertValid($message);
        } catch (Exception $exception) {
            $this->logger->error('Validation failed', ['errors' => $exception->getMessage()]);

            return;
        }

        $monster = $this->spawnMonster->spawn(
            $message->firstName,
            $message->lastName,
            $message->categoryCode,
            $message->level,
        );


        $this->logger->debug('Monster created', [
            'id' => $monster->getId(),
            'max_health' => $monster->getMaxHealth(),
            'defense' => $monster->getDefense(),
        ]);
    }
}
